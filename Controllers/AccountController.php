<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // Show all accounts belonging to the logged-in user
    public function index()
    {
        $accounts = Auth::user()->accounts()->get();
        return view('accounts.index', compact('accounts'));
    }

    // Show the form to open a new savings or current account
    public function create()
    {
        return view('accounts.create');
    }

    // Validate input, auto-generate account number, and save new account
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_type' => ['required', 'in:savings,current'],
            'currency'     => ['required', 'string', 'size:3'],
        ]);

        Account::create([
            'user_id'        => Auth::id(),
            'account_number' => Account::generateAccountNumber(),
            'account_type'   => $validated['account_type'],
            'balance'        => 0.00,
            'currency'       => $validated['currency'],
            'status'         => 'active',
            'opened_at'      => now()->toDateString(),
        ]);

        return redirect()->route('accounts.index')->with('success', 'New account opened successfully!');
    }

    // Display full account details and its paginated transaction history
    public function show(Account $account)
    {
        if ($account->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $transactions = \App\Models\Transaction::where('sender_account_id', $account->id)
            ->orWhere('receiver_account_id', $account->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('accounts.show', compact('account', 'transactions'));
    }

    // Customer requests account closure; sets status to 'frozen' if balance is zero
    public function closeRequest(Account $account)
    {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        if ($account->balance > 0) {
            return back()->with('error', 'You must withdraw all funds before closing this account. Current balance: ৳' . number_format($account->balance, 2));
        }

        if ($account->status === 'closed') {
            return back()->with('error', 'This account is already closed.');
        }

        $account->update(['status' => 'frozen']);

        return back()->with('success', 'Close request submitted. Your account has been frozen pending admin review.');
    }

    // Show the profile edit form with current user data
    public function editProfile()
    {
        $user = Auth::user();
        return view('accounts.profile', compact('user'));
    }

    // Update name/phone; optionally change password after verifying current one
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'  => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
        ];

        if ($request->phone !== $user->phone) {
            $rules['phone'][] = 'unique:users,phone';
        }

        $validated = $request->validate($rules);

        if ($request->filled('new_password')) {
            $request->validate([
                'current_password' => ['required'],
                'new_password'     => ['required', 'confirmed', 'min:8'],
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $validated['password'] = Hash::make($request->new_password);
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
    // Admin permanently deletes a frozen account (balance must be zero)
    public function adminApproveClosure(Account $account)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($account->status !== 'frozen') {
            return back()->with('error', 'This account has not requested closure.');
        }

        if ($account->balance > 0) {
            return back()->with('error', 'Cannot close account with remaining balance.');
        }

        $accountNumber = $account->account_number;
        $account->delete();

        return back()->with('success', 'Account #' . $accountNumber . ' has been permanently closed.');
    }

    // Admin toggles account status between active and frozen
    public function adminToggleAccountStatus(Account $account)
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $account->status = ($account->status === 'active') ? 'frozen' : 'active';
        $account->save();

        return back()->with('success', "Account {$account->account_number} status updated to " . ucfirst($account->status));
    }
}
