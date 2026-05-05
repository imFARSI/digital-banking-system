<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Reward;
use App\Models\RewardTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    // List all customer accounts with optional search by name or email
    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::where('role', 'customer')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }

    // Show full details for a single user: accounts, cards, loans, tickets, rewards
    public function show(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot view admin details here.');
        }

        $user->load([
            'accounts.cards',
            'accounts.savingsPlans',
            'loans.loanProduct',
            'tickets',
            'reward.rewardTier'
        ]);

        $totalBalance = $user->accounts()->sum('balance');
        $activeLoansCount = $user->loans()->where('status', 'active')->count();
        $pendingLoansCount = $user->loans()->where('status', 'pending')->count();

        $accountIds = $user->accounts()->pluck('id');
        $recentTransactions = \App\Models\Transaction::whereIn('sender_account_id', $accountIds)
            ->orWhereIn('receiver_account_id', $accountIds)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.users.show', compact('user', 'totalBalance', 'activeLoansCount', 'pendingLoansCount', 'recentTransactions'));
    }

    // Show the form for an admin to manually create a new customer account
    public function create()
    {
        return view('admin.users.create');
    }

    // Save a new user created by admin, auto-creating their account and reward record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:150', 'unique:users,email', 'regex:/@gmail\.com$/i'],
            'phone'         => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password'      => ['required', 'string', Password::min(8)],
            'nid'           => ['required', 'string', 'max:30', 'unique:users,nid'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender'        => ['required', 'in:male,female,other'],
        ], [
            'email.regex'  => 'Only Gmail addresses (@gmail.com) are accepted.',
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This phone number is already in use.',
            'nid.unique'   => 'This National ID is already registered.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role']     = 'customer';
        $validated['status']   = 'active';

        $user = User::create($validated);

        Account::create([
            'user_id'        => $user->id,
            'account_number' => Account::generateAccountNumber(),
            'account_type'   => 'savings',
            'balance'        => 0.00,
            'currency'       => 'BDT',
            'status'         => 'active',
            'opened_at'      => now()->toDateString(),
        ]);

        $tier = RewardTier::orderBy('min_points', 'asc')->first();
        if ($tier) {
            Reward::create([
                'user_id'         => $user->id,
                'reward_tier_id'  => $tier->id,
                'total_points'    => 0,
                'redeemed_points' => 0,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully.");
    }

    // Toggle a customer's status between active and suspended
    public function toggleStatus(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin account.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'suspended' : 'active',
        ]);

        $msg = $user->status === 'active' ? 'activated' : 'suspended';
        return back()->with('success', "User {$user->name} has been {$msg}.");
    }
    // Permanently delete a user and all their associated data (accounts, loans, tickets, etc.)
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin account.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $user->accounts()->each(function ($account) {
                $account->cards()->delete();
                $account->savingsPlans()->delete();
                $account->payments()->delete();
                \App\Models\Transaction::where('sender_account_id', $account->id)
                    ->orWhere('receiver_account_id', $account->id)
                    ->delete();
                $account->delete();
            });

            $user->loans()->each(function ($loan) {
                $loan->repayments()->delete();
                $loan->delete();
            });

            $user->tickets()->each(function ($ticket) {
                $ticket->replies()->delete();
                $ticket->delete();
            });

            if ($user->reward) {
                $user->reward()->delete();
            }
            $user->notifications()->delete();
            
            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} and all associated data have been permanently deleted.");
    }
}
