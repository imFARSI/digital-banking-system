<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\LoanRepayment;
use App\Models\SavingsPlan;
use App\Models\Payment;
use App\Models\Account;
use App\Models\BillCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ServiceController extends Controller
{
    // Services landing page
    public function index()
    {
        return view('services.index');
    }

    // List all cards linked to the user's accounts
    public function cardsIndex()
    {
        $cards = Auth::user()
            ->accounts()
            ->with('cards')
            ->get()
            ->pluck('cards')
            ->flatten();

        $accounts = Auth::user()->accounts()->where('status', 'active')->get();

        return view('services.cards', compact('cards', 'accounts'));
    }

    // Issue a new Visa card with auto-generated number and 3-year expiry
    public function requestCard(Request $request)
    {
        $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'card_type'  => ['required', 'in:debit,credit,prepaid'],
        ]);

        $account = Account::where('id', $request->account_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Card::create([
            'account_id'      => $account->id,
            'card_number'     => '4' . mt_rand(100000000000000, 999999999999999),
            'card_type'       => $request->card_type,
            'network'         => 'visa',
            'cardholder_name' => Auth::user()->name,
            'expiry_month'    => now()->addYears(3)->month,
            'expiry_year'     => now()->addYears(3)->year,
            'cvv_hash'        => bcrypt(mt_rand(100, 999)),
            'status'          => 'active',
            'issued_at'       => now()->toDateString(),
        ]);

        return redirect()->route('services.cards')->with('success', 'Card issued successfully.');
    }

    // Admin blocks a card (status -> blocked)
    public function freezeCard(Card $card)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $card->update(['status' => 'blocked']);

        return back()->with('success', 'Card ending in ' . substr($card->card_number, -4) . ' has been frozen.');
    }

    // Admin reactivates a blocked card (status -> active)
    public function unfreezeCard(Card $card)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $card->update(['status' => 'active']);

        return back()->with('success', 'Card ending in ' . substr($card->card_number, -4) . ' has been reactivated.');
    }

    // Pay using a card; debits account and logs transaction
    public function cardPayment(Request $request)
    {
        $request->validate([
            'card_id'         => ['required', 'exists:cards,id'],
            'payee_reference' => ['required', 'string'],
            'amount'          => ['required', 'numeric', 'min:1'],
        ]);

        $card = Card::with('account')->findOrFail($request->card_id);
        $account = $card->account;

        if ($account->user_id !== Auth::id()) abort(403);
        if ($card->status !== 'active') return back()->with('error', 'This card is currently frozen.');
        if ($account->balance < $request->amount) return back()->with('error', 'Insufficient balance on the linked account.');

        try {
            DB::transaction(function () use ($account, $card, $request) {
                $account->decrement('balance', $request->amount);

                $txn = Transaction::create([
                    'reference_code'    => Transaction::generateReferenceCode(),
                    'type'              => 'card_payment',
                    'sender_account_id' => $account->id,
                    'amount'            => $request->amount,
                    'status'            => 'completed',
                    'description'       => 'Card Payment (Card: ' . substr($card->card_number, -4) . ') to ' . $request->payee_reference,
                    'processed_at'      => now(),
                ]);

                Auth::user()->addRewardPoints(5);
            });

            return back()->with('success', 'Payment of ৳' . number_format($request->amount, 2) . ' successful via Card.');
        } catch (Exception $e) {
            return back()->with('error', 'Payment failed.');
        }
    }

    // Show user's loans and available loan products
    public function loansIndex()
    {
        $loans    = Auth::user()->loans()->with('loanProduct')->latest()->get();
        $types    = LoanProduct::where('is_active', true)->get();
        $accounts = Auth::user()->accounts()->where('status', 'active')->get();
        return view('services.loans', compact('loans', 'types', 'accounts'));
    }

    // Make a repayment on an active loan; marks paid_off when balance reaches zero
    public function repayLoan(Request $request, Loan $loan)
    {
        $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'amount'     => ['required', 'numeric', 'min:1'],
        ]);

        if ($loan->user_id !== Auth::id()) abort(403);
        if (!in_array($loan->status, ['active', 'disbursed'])) return back()->with('error', 'Loan is not in a repayable state.');

        $account = $loan->account;
        if (!$account) return back()->with('error', 'Linked account not found.');

        $amount = (float)$request->amount;
        if ($amount <= 0) return back()->with('error', 'Invalid repayment amount.');

        if ($account->balance < $amount) return back()->with('error', 'Insufficient balance in the linked account.');
        
        $repayAmount = $amount;
        if ($repayAmount > $loan->outstanding_balance) {
            $repayAmount = $loan->outstanding_balance;
        }

        try {
            DB::transaction(function () use ($loan, $account, $repayAmount) {
                $account->balance = (float)$account->balance - (float)$repayAmount;
                $account->save();

                $loan->outstanding_balance = (float)$loan->outstanding_balance - (float)$repayAmount;
                
                if ($loan->outstanding_balance <= 0.01) {
                    $loan->outstanding_balance = 0;
                    $loan->status = 'paid_off';
                }
                $loan->save();

                Transaction::create([
                    'reference_code'    => Transaction::generateReferenceCode(),
                    'type'              => 'payment',
                    'sender_account_id' => $account->id,
                    'amount'            => $repayAmount,
                    'status'            => 'completed',
                    'description'       => 'Loan Repayment (Loan #' . $loan->id . ')',
                    'processed_at'      => now(),
                ]);

                Auth::user()->addRewardPoints(5);
            });

            return back()->with('success', 'Repayment successful.');
        } catch (Exception $e) {
            return back()->with('error', 'Repayment failed: ' . $e->getMessage());
        }
    }

    // Submit a loan application; notifies admins for review
    public function applyLoan(Request $request)
    {
        $request->validate([
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'account_id'      => ['required', 'exists:accounts,id'],
            'principal'       => ['required', 'numeric', 'min:1000', 'max:1000000000'],
            'tenure_months'   => ['required', 'integer', 'min:1', 'max:360'],
        ]);

        $product = LoanProduct::findOrFail($request->loan_product_id);

        $calc = Loan::calculateTotals($request->principal, $product->interest_rate, $request->tenure_months);

        $loan = Loan::create([
            'user_id'             => Auth::id(),
            'account_id'          => $request->account_id,
            'loan_product_id'     => $product->id,
            'principal'           => $request->principal,
            'interest_rate'       => $product->interest_rate,
            'tenure_months'       => $request->tenure_months,
            'monthly_installment' => $calc['monthly'],
            'outstanding_balance' => $calc['total'],
            'status'              => 'pending',
        ]);

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->sendNotification([
                'title'      => 'New Loan Request',
                'message'    => Auth::user()->name . ' requested a loan of ৳' . number_format($request->principal),
                'action_url' => route('admin.loans'),
                'icon'       => 'bi-cash',
                'color'      => 'warning',
            ]);
        }

        return back()->with('success', 'Loan application submitted. Awaiting admin approval.');
    }

    // Admin view of all pending and active loans
    public function adminLoansIndex()
    {
        $pendingLoans = Loan::where('status', 'pending')->with('user', 'account', 'loanProduct')->get();
        $activeLoans  = Loan::where('status', 'active')->with('user', 'account')->get();
        return view('admin.loans', compact('pendingLoans', 'activeLoans'));
    }

    // Admin approves loan: disburses principal and creates repayment schedule
    public function approveLoan(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'This loan is not in pending state.');
        }

        try {
            DB::transaction(function () use ($loan) {
                if ($loan->disburse()) {
                    Transaction::create([
                        'reference_code'      => Transaction::generateReferenceCode(),
                        'type'                => 'loan_disbursement',
                        'receiver_account_id' => $loan->account_id,
                        'amount'              => $loan->principal,
                        'status'              => 'completed',
                        'description'         => 'Loan Disbursement #' . $loan->id,
                        'processed_at'        => now(),
                    ]);

                    $loan->user->sendNotification([
                        'title'      => 'Loan Approved',
                        'message'    => 'Your loan request for ৳' . number_format($loan->principal) . ' has been approved and disbursed.',
                        'action_url' => route('services.loans'),
                        'icon'       => 'bi-check-circle',
                        'color'      => 'success',
                    ]);
                }
            });

            return back()->with('success', 'Loan approved and disbursed successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    // Admin rejects a pending loan and notifies the user
    public function rejectLoan(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'This loan is not in pending state.');
        }

        $loan->update(['status' => 'rejected']);

        $loan->user->notify(new \App\Notifications\SystemNotification([
            'title'      => 'Loan Rejected',
            'message'    => 'Your loan request for ৳' . number_format($loan->principal) . ' has been rejected.',
            'action_url' => route('services.index'),
            'icon'       => 'bi-x-circle',
            'color'      => 'danger',
        ]));

        return back()->with('success', 'Loan application rejected.');
    }

    // Show user's active savings plans and accounts for new plan
    public function savingsIndex()
    {
        $accountIds = Auth::user()->accounts()->pluck('id');
        $savings    = SavingsPlan::whereIn('account_id', $accountIds)->get();
        $accounts   = Auth::user()->accounts()->where('status', 'active')->get();
        return view('services.savings', compact('savings', 'accounts'));
    }

    // Open a DPS or FDR plan; deducts deposit from account balance
    public function storeSavings(Request $request)
    {
        $request->validate([
            'account_id'     => ['required', 'exists:accounts,id'],
            'plan_type'      => ['required', 'in:dps,fdr'],
            'deposit_amount' => ['required', 'numeric', 'min:500'],
            'tenure_months'  => ['required', 'integer', 'min:3', 'max:120'],
        ]);

        $account = Account::where('id', $request->account_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$account->isActive()) return back()->with('error', 'Account is inactive.');
        if ($account->balance < $request->deposit_amount) return back()->with('error', 'Insufficient balance for this plan.');

        $calc = SavingsPlan::calculateMaturity($request->deposit_amount, $request->plan_type, $request->tenure_months);

        try {
            DB::transaction(function () use ($account, $request, $calc) {
                $account->decrement('balance', $request->deposit_amount);

                SavingsPlan::create([
                    'account_id'     => $account->id,
                    'plan_type'      => $request->plan_type,
                    'deposit_amount' => $request->deposit_amount,
                    'interest_rate'  => $calc['rate'],
                    'tenure_months'  => $request->tenure_months,
                    'maturity_date'  => now()->addMonths((int)$request->tenure_months)->toDateString(),
                    'maturity_amount'=> $calc['amount'],
                    'status'         => 'active',
                    'started_at'     => now()->toDateString(),
                ]);

                Auth::user()->addRewardPoints(5);
            });

            return back()->with('success', strtoupper($request->plan_type) . ' plan opened successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Could not open savings plan. Please try again.');
        }
    }

    // Show bill categories, recent payments, and active cards
    public function paymentsIndex()
    {
        $categories   = BillCategory::where('is_active', true)->get();
        $accountIds   = Auth::user()->accounts()->pluck('id');
        $recentPayments = Payment::whereIn('account_id', $accountIds)
            ->with('billCategory')
            ->latest()
            ->limit(10)
            ->get();
        
        $cards = Auth::user()
            ->accounts()
            ->with('cards')
            ->get()
            ->pluck('cards')
            ->flatten()
            ->where('status', 'active');

        return view('services.payments', compact('categories', 'recentPayments', 'cards'));
    }

    // Pay a utility bill via card; creates Transaction and Payment records
    public function payBill(Request $request)
    {
        $request->validate([
            'card_id'          => ['required', 'exists:cards,id'],
            'bill_category_id' => ['required', 'exists:bill_categories,id'],
            'payee_reference'  => ['required', 'string', 'max:100'],
            'amount'           => ['required', 'numeric', 'min:1'],
        ]);

        $card = Card::with('account')->findOrFail($request->card_id);
        $account = $card->account;

        if ($account->user_id !== Auth::id()) abort(403);
        if ($card->status !== 'active') return back()->with('error', 'Card is frozen.');
        if ($account->balance < $request->amount) return back()->with('error', 'Insufficient balance.');

        try {
            DB::transaction(function () use ($account, $card, $request) {
                $account->decrement('balance', $request->amount);

                $txn = Transaction::create([
                    'reference_code'    => Transaction::generateReferenceCode(),
                    'type'              => 'payment',
                    'sender_account_id' => $account->id,
                    'amount'            => $request->amount,
                    'status'            => 'completed',
                    'description'       => 'Bill Payment via Card ' . substr($card->card_number, -4),
                    'processed_at'      => now(),
                ]);

                Payment::create([
                    'account_id'       => $account->id,
                    'transaction_id'   => $txn->id,
                    'bill_category_id' => $request->bill_category_id,
                    'payment_type'     => 'bill',
                    'payee_reference'  => $request->payee_reference,
                    'amount'           => $request->amount,
                    'status'           => 'completed',
                    'paid_at'          => now(),
                ]);

                Auth::user()->addRewardPoints(5);
            });

            return back()->with('success', 'Bill payment processed successfully via Card.');
        } catch (Exception $e) {
            return back()->with('error', 'Payment failed.');
        }
    }

    // Recharge a mobile number via card with fixed BDT amounts
    public function mobileRecharge(Request $request)
    {
        $request->validate([
            'card_id'       => ['required', 'exists:cards,id'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'operator'      => ['required', 'in:Grameenphone,Robi,Banglalink,Teletalk,Airtel'],
            'amount'        => ['required', 'numeric', 'in:10,20,50,100,200,300,500'],
        ]);

        $card = Card::with('account')->findOrFail($request->card_id);
        $account = $card->account;

        if ($account->user_id !== Auth::id()) abort(403);
        if ($card->status !== 'active') return back()->with('error', 'The selected card is frozen.');
        if ($account->balance < $request->amount) return back()->with('error', 'Insufficient balance.');

        $category = BillCategory::firstOrCreate(
            ['name' => 'Mobile Recharge'],
            ['is_active' => true]
        );

        try {
            DB::transaction(function () use ($account, $card, $request, $category) {
                $account->decrement('balance', $request->amount);

                $txn = Transaction::create([
                    'reference_code'    => Transaction::generateReferenceCode(),
                    'type'              => 'payment',
                    'sender_account_id' => $account->id,
                    'amount'            => $request->amount,
                    'status'            => 'completed',
                    'description'       => 'Mobile Recharge (' . $request->operator . ') via Card ending in ' . substr($card->card_number, -4),
                    'processed_at'      => now(),
                ]);

                Payment::create([
                    'account_id'       => $account->id,
                    'transaction_id'   => $txn->id,
                    'bill_category_id' => $category->id,
                    'payment_type'     => 'mobile_recharge',
                    'payee_reference'  => $request->mobile_number,
                    'amount'           => $request->amount,
                    'status'           => 'completed',
                    'paid_at'          => now(),
                ]);

                Auth::user()->addRewardPoints(5);
            });

            return back()->with('success', '৳' . $request->amount . ' recharged via Card to ' . $request->mobile_number . '.');
        } catch (Exception $e) {
            return back()->with('error', 'Recharge failed.');
        }
    }

    // Admin view of all cards and accounts including closure requests
    public function adminCardsAccountsIndex()
    {
        $cards = Card::with('account.user')->latest()->get();
        $accounts = Account::with('user')->latest()->get();
        $closureRequests = Account::where('status', 'frozen')->with('user')->get();

        return view('admin.cards_accounts', compact('cards', 'accounts', 'closureRequests'));
    }

    // Admin toggles a card between active and blocked
    public function adminToggleCardStatus(Card $card)
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $card->status = ($card->status === 'active') ? 'blocked' : 'active';
        $card->save();

        return back()->with('success', "Card ending in " . substr($card->card_number, -4) . " status updated to " . ucfirst($card->status));
    }

    // Show reward points balance and available redemption tiers
    public function rewardsIndex()
    {
        $user = Auth::user();
        $accounts = $user->accounts()->where('status', 'active')->get();
        return view('services.rewards', compact('user', 'accounts'));
    }

    // Exchange reward points for cashback deposited into selected account
    public function redeemRewards(Request $request)
    {
        $request->validate([
            'points'     => ['required', 'integer', 'in:15,50,100'],
            'account_id' => ['required', 'exists:accounts,id'],
        ]);

        $user = Auth::user();
        if ($user->reward_points < $request->points) {
            return back()->with('error', 'Insufficient reward points.');
        }

        $account = $user->accounts()->where('id', $request->account_id)->firstOrFail();
        
        $cashbackMap = [
            15 => 5,
            50 => 25,
            100 => 50
        ];

        $cashback = $cashbackMap[$request->points];

        try {
            DB::transaction(function () use ($user, $account, $request, $cashback) {
                $user->decrement('reward_points', $request->points);

                $account->increment('balance', $cashback);

                Transaction::create([
                    'reference_code'      => Transaction::generateReferenceCode(),
                    'type'                => 'deposit',
                    'receiver_account_id' => $account->id,
                    'amount'              => $cashback,
                    'status'              => 'completed',
                    'description'         => 'Reward Points Redemption (' . $request->points . ' pts)',
                    'processed_at'        => now(),
                ]);
            });

            return back()->with('success', 'Redemption successful! ৳' . $cashback . ' added to your account.');
        } catch (Exception $e) {
            return back()->with('error', 'Redemption failed: ' . $e->getMessage());
        }
    }
}
