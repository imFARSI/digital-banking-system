<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    // Show paginated transaction history (sent + received) for current user
    public function index()
    {
        $user = Auth::user();
        $accountIds = $user->accounts()->pluck('id');

        $transactions = Transaction::whereIn('sender_account_id', $accountIds)
            ->orWhereIn('receiver_account_id', $accountIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $accounts = $user->accounts()->where('status', 'active')->get();

        return view('transactions.index', compact('transactions', 'accounts'));
    }

    // Atomically transfer funds between two accounts; +5 reward points
    public function transfer(Request $request)
    {
        $request->validate([
            'sender_account_id'   => ['required', 'exists:accounts,id'],
            'receiver_account_no' => ['required', 'exists:accounts,account_number'],
            'amount'              => ['required', 'numeric', 'min:10'],
            'description'         => ['nullable', 'string', 'max:255'],
        ]);

        $sender   = Account::findOrFail($request->sender_account_id);
        $receiver = Account::where('account_number', $request->receiver_account_no)->firstOrFail();

        if ($sender->user_id !== Auth::id()) abort(403);
        if (!$sender->isActive() || !$receiver->isActive()) return back()->with('error', 'One or both accounts are inactive.');
        if ($sender->id === $receiver->id) return back()->with('error', 'Cannot transfer to the same account.');
        if ($sender->balance < $request->amount) return back()->with('error', 'Insufficient balance.');

        try {
            DB::transaction(function () use ($sender, $receiver, $request) {
                $sender->decrement('balance', $request->amount);
                $receiver->increment('balance', $request->amount);

                $txn = Transaction::create([
                    'reference_code'      => Transaction::generateReferenceCode(),
                    'type'                => 'transfer',
                    'sender_account_id'   => $sender->id,
                    'receiver_account_id' => $receiver->id,
                    'amount'              => $request->amount,
                    'fee'                 => 0.00,
                    'currency'            => $sender->currency,
                    'status'              => 'completed',
                    'description'         => $request->description ?? 'Fund Transfer',
                    'ip_address'          => request()->ip(),
                    'processed_at'        => now(),
                ]);

                $this->earnPoints(Auth::user(), 5, 'Transfer reward', $txn->id);
            });

            return redirect()->route('transactions.index')->with('success', 'Transfer successful!');
        } catch (Exception $e) {
            return back()->with('error', 'Transaction failed. Please try again.');
        }
    }

    // Deposit cash into an account; creates transaction record + reward points
    public function deposit(Request $request)
    {
        $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'amount'     => ['required', 'numeric', 'min:10'],
        ]);

        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== Auth::id() || !$account->isActive()) abort(403);

        try {
            DB::transaction(function () use ($account, $request) {
                $account->increment('balance', $request->amount);

                $txn = Transaction::create([
                    'reference_code'      => Transaction::generateReferenceCode(),
                    'type'                => 'deposit',
                    'receiver_account_id' => $account->id,
                    'amount'              => $request->amount,
                    'status'              => 'completed',
                    'description'         => 'Cash Deposit',
                    'processed_at'        => now(),
                ]);

                $this->earnPoints(Auth::user(), 5, 'Deposit reward', $txn->id);
            });

            return back()->with('success', 'Deposit of ৳' . number_format($request->amount, 2) . ' successful!');
        } catch (Exception $e) {
            return back()->with('error', 'Deposit failed. Please try again.');
        }
    }

    // Withdraw cash from account with balance check; +5 reward points
    public function withdraw(Request $request)
    {
        $request->validate([
            'account_id'  => ['required', 'exists:accounts,id'],
            'amount'      => ['required', 'numeric', 'min:10'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== Auth::id()) abort(403);
        if (!$account->isActive()) return back()->with('error', 'Account is inactive.');
        if ($account->balance < $request->amount) return back()->with('error', 'Insufficient balance. Available: ৳' . number_format($account->balance, 2));

        try {
            DB::transaction(function () use ($account, $request) {
                $account->decrement('balance', $request->amount);

                $txn = Transaction::create([
                    'reference_code'    => Transaction::generateReferenceCode(),
                    'type'              => 'withdrawal',
                    'sender_account_id' => $account->id,
                    'amount'            => $request->amount,
                    'status'            => 'completed',
                    'description'       => $request->description ?? 'Cash Withdrawal',
                    'ip_address'        => request()->ip(),
                    'processed_at'      => now(),
                ]);

                $this->earnPoints(Auth::user(), 5, 'Withdrawal reward', $txn->id);
            });

            return back()->with('success', 'Withdrawal of ৳' . number_format($request->amount, 2) . ' successful!');
        } catch (Exception $e) {
            return back()->with('error', 'Withdrawal failed. Please try again.');
        }
    }

    // Admin view of all system transactions with sender/receiver eager loaded
    public function adminIndex()
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $transactions = Transaction::with(['senderAccount.user', 'receiverAccount.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        $totalVolume = Transaction::where('status', 'completed')->sum('amount');
        $totalFees = Transaction::where('status', 'completed')->sum('fee');
        $totalTxns = Transaction::count();

        return view('admin.transactions', compact('transactions', 'totalVolume', 'totalFees', 'totalTxns'));
    }

    // Private helper: adds reward points to user after each transaction
    private function earnPoints($user, int $points, string $note, $refId = null)
    {
        if ($points > 0) {
            $user->addRewardPoints($points);
        }
    }
}
