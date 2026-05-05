<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    // List tickets: admin sees all, customer sees only their own
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $tickets = SupportTicket::orderBy('updated_at', 'desc')->paginate(15);
        } else {
            $tickets = Auth::user()->tickets()->orderBy('updated_at', 'desc')->paginate(15);
        }
        return view('support.index', compact('tickets'));
    }

    // Show the form to create a new support ticket
    public function create()
    {
        return view('support.create');
    }

    // Create ticket + first reply, then notify all admins
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:account,transaction,card,loan,technical,other'],
            'subject'  => ['required', 'string', 'max:255'],
            'message'  => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        $ticket = SupportTicket::create([
            'user_id'       => Auth::id(),
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'category'      => $validated['category'],
            'subject'       => $validated['subject'],
            'priority'      => $validated['priority'],
            'status'        => 'open',
        ]);

        TicketReply::create([
            'ticket_id'   => $ticket->id,
            'sender_id'   => Auth::id(),
            'sender_role' => Auth::user()->role,
            'message'     => $validated['message'],
        ]);

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->sendNotification([
                'title'      => 'New Support Ticket',
                'message'    => Auth::user()->name . ' created ticket #' . $ticket->ticket_number,
                'action_url' => route('admin.support.show', $ticket->id),
                'icon'       => 'bi-ticket',
                'color'      => 'warning',
            ]);
        }

        return redirect()->route('support.show', $ticket->id)->with('success', 'Support ticket created successfully.');
    }

    // Show a single ticket with all replies in chronological order
    public function show($id)
    {
        $ticket = SupportTicket::with(['replies' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        if ($ticket->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('support.show', compact('ticket'));
    }

    // Add a reply; notify the other party and update ticket status if needed
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $ticket = SupportTicket::findOrFail($id);

        if ($ticket->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        TicketReply::create([
            'ticket_id'   => $ticket->id,
            'sender_id'   => Auth::id(),
            'sender_role' => Auth::user()->role,
            'message'     => $request->message,
        ]);

        if (Auth::user()->isAdmin()) {
            $ticket->user->sendNotification([
                'title'      => 'New Ticket Reply',
                'message'    => 'Admin replied to your ticket #' . $ticket->ticket_number,
                'action_url' => route('support.show', $ticket->id),
                'icon'       => 'bi-reply',
                'color'      => 'info',
            ]);
        } else {
            if ($ticket->status === 'resolved') {
                $ticket->update(['status' => 'open']);
            }

            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->sendNotification([
                    'title'      => 'New Ticket Reply',
                    'message'    => $ticket->user->name . ' replied to ticket #' . $ticket->ticket_number,
                    'action_url' => route('admin.support.show', $ticket->id),
                    'icon'       => 'bi-reply',
                    'color'      => 'info',
                ]);
            }
        }

        return back()->with('success', 'Reply submitted.');
    }

    // Admin marks ticket as resolved and notifies the customer
    public function resolve($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'resolved']);

        $ticket->user->sendNotification([
            'title'      => 'Ticket Resolved',
            'message'    => 'Your ticket #' . $ticket->ticket_number . ' has been resolved.',
            'action_url' => route('support.show', $ticket->id),
            'icon'       => 'bi-check-circle',
            'color'      => 'success',
        ]);

        return back()->with('success', 'Ticket marked as resolved.');
    }
}
