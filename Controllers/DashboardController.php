<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Loan;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Customer dashboard: loads account balances, recent transactions, loan and ticket counts
    public function index()
    {
        $user = Auth::user();

        $accounts    = $user->accounts()->where('status', 'active')->get();
        $totalBalance = $accounts->sum('balance');

        $recentTransactions = Transaction::where(function ($q) use ($accounts) {
            $accountIds = $accounts->pluck('id');
            $q->whereIn('sender_account_id', $accountIds)
              ->orWhereIn('receiver_account_id', $accountIds);
        })
        ->where('status', 'completed')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

        $activeLoans   = $user->loans()->where('status', 'active')->count();
        $pendingLoans  = $user->loans()->where('status', 'pending')->count();
        $openTickets   = $user->tickets()->whereIn('status', ['open', 'in_progress'])->count();
        $rewardPoints  = $user->reward_points;

        return view('dashboard.index', compact(
            'user',
            'accounts',
            'totalBalance',
            'recentTransactions',
            'activeLoans',
            'pendingLoans',
            'openTickets',
            'rewardPoints'
        ));
    }

    // Admin dashboard: system-wide totals for users, accounts, volume, loans, and tickets
    public function adminIndex()
    {
        $totalUsers       = \App\Models\User::where('role', 'customer')->count();
        $totalAccounts    = Account::count();
        $systemBalance    = Account::where('status', 'active')->sum('balance');
        $todayTransactions = Transaction::whereDate('created_at', today())
                                ->where('status', 'completed')
                                ->count();
        $todayVolume      = Transaction::whereDate('created_at', today())
                                ->where('status', 'completed')
                                ->sum('amount');
        $pendingLoans     = Loan::where('status', 'pending')->count();
        $openTickets      = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        $recentTransactions = Transaction::whereDate('created_at', today())
                                ->with(['senderAccount.user', 'receiverAccount.user'])
                                ->latest()
                                ->limit(5)
                                ->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalAccounts',
            'systemBalance',
            'todayTransactions',
            'todayVolume',
            'pendingLoans',
            'openTickets',
            'recentTransactions'
        ));
    }
}
