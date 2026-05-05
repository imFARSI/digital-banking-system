@extends('layouts.app')

@section('page_title', 'Financial Services')

{{-- Page: All financial services in a tabbed layout (Cards, Loans, Savings, Bill Payment, Recharge) --}}
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <ul class="nav nav-tabs border-bottom-0" id="servicesTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-medium" data-bs-toggle="tab" data-bs-target="#cards-pane" type="button">
                    <i class="bi bi-credit-card me-2"></i>Cards
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#loans-pane" type="button">
                    <i class="bi bi-cash-coin me-2"></i>Loans
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#savings-pane" type="button">
                    <i class="bi bi-piggy-bank me-2"></i>Savings (DPS/FDR)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#bill-pane" type="button">
                    <i class="bi bi-receipt me-2"></i>Bill Payment
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-medium" data-bs-toggle="tab" data-bs-target="#recharge-pane" type="button">
                    <i class="bi bi-phone me-2"></i>Mobile Recharge
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content border-top" id="servicesTabContent">

        {{-- Cards tab: shows issued cards with freeze/unfreeze buttons --}}
    {{-- ══════════ CARDS TAB ══════════ --}}
        <div class="tab-pane fade show active p-4" id="cards-pane">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">My Cards</h5>
                <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#requestCardModal">
                    <i class="bi bi-plus-lg me-1"></i>Request New Card
                </button>
            </div>

            @php
                $cards = Auth::user()->accounts()->with('cards')->get()->pluck('cards')->flatten();
                $accounts = Auth::user()->accounts()->where('status','active')->get();
            @endphp

            <div class="row g-3">
                @forelse($cards as $card)
                <div class="col-md-6">
                    <div class="card border-0 rounded-4 text-white p-3" style="background: linear-gradient(135deg, #0A2540, #1a4b77);">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <span class="badge bg-white text-dark me-1">{{ strtoupper($card->card_type) }}</span>
                                @if($card->status === 'blocked')
                                    <span class="badge bg-danger">FROZEN</span>
                                @else
                                    <span class="badge bg-success">ACTIVE</span>
                                @endif
                            </div>
                            <i class="bi bi-wifi fs-4"></i>
                        </div>
                        <h4 class="font-monospace mb-4">**** **** **** {{ substr($card->card_number, -4) }}</h4>
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <small class="text-white-50 d-block">Card Holder</small>
                                <span class="fw-medium">{{ strtoupper($card->cardholder_name) }}</span>
                            </div>
                            <div>
                                <small class="text-white-50 d-block">Expires</small>
                                <span>{{ str_pad($card->expiry_month,2,'0',STR_PAD_LEFT) }}/{{ substr($card->expiry_year,-2) }}</span>
                            </div>
                            <div>
                                @if($card->status === 'active')
                                    <form action="{{ route('services.cards.freeze', $card) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-light">
                                            <i class="bi bi-lock me-1"></i>Freeze
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('services.cards.unfreeze', $card) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-unlock me-1"></i>Unfreeze
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">No cards yet. Request your first card.</div>
                @endforelse
            </div>
        </div>

        {{-- Loans tab: shows user's loan list and apply-loan modal --}}
        {{-- ══════════ LOANS TAB ══════════ --}}
        <div class="tab-pane fade p-4" id="loans-pane">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">My Loans</h5>
                <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#applyLoanModal">
                    <i class="bi bi-plus-lg me-1"></i>Apply for Loan
                </button>
            </div>
            @php
                $loans = Auth::user()->loans()->with('loanProduct')->get();
                $loanProducts = \App\Models\LoanProduct::where('is_active', true)->get();
                $userAccounts = Auth::user()->accounts()->where('status','active')->get();
            @endphp
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Principal</th>
                            <th>Monthly EMI</th>
                            <th>Tenure</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td class="fw-medium">{{ $loan->loanProduct->name ?? 'Standard Loan' }}</td>
                                <td>৳{{ number_format($loan->principal, 2) }}</td>
                                <td>৳{{ number_format($loan->monthly_installment, 2) }}</td>
                                <td>{{ $loan->tenure_months }} months</td>
                                <td>৳{{ number_format($loan->outstanding_balance, 2) }}</td>
                                <td>
                                    @php $badgeMap = ['pending'=>'warning','active'=>'success','rejected'=>'danger','paid_off'=>'info','defaulted'=>'dark']; @endphp
                                    <span class="badge bg-{{ $badgeMap[$loan->status] ?? 'secondary' }}">{{ ucfirst($loan->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">No loan applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Savings tab: DPS and FDR plan list with open-plan modal --}}
        {{-- ══════════ SAVINGS TAB ══════════ --}}
        <div class="tab-pane fade p-4" id="savings-pane">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Fixed Deposits & DPS</h5>
                <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#savingsModal">
                    <i class="bi bi-plus-lg me-1"></i>Open New Plan
                </button>
            </div>
            @php
                $savingsAccountIds = Auth::user()->accounts()->pluck('id');
                $savingsPlans = \App\Models\SavingsPlan::whereIn('account_id', $savingsAccountIds)->get();
            @endphp
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Deposited</th>
                            <th>Rate</th>
                            <th>Tenure</th>
                            <th>Maturity Amount</th>
                            <th>Maturity Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($savingsPlans as $plan)
                            <tr>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ strtoupper($plan->plan_type) }}</span></td>
                                <td>৳{{ number_format($plan->deposit_amount, 2) }}</td>
                                <td>{{ $plan->interest_rate }}%</td>
                                <td>{{ $plan->tenure_months }} months</td>
                                <td class="fw-bold text-success">৳{{ number_format($plan->maturity_amount, 2) }}</td>
                                <td>{{ $plan->maturity_date->format('d M Y') }}</td>
                                <td><span class="badge bg-{{ $plan->status=='active' ? 'success' : 'secondary' }}">{{ ucfirst($plan->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">No savings plans yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════ BILL PAYMENT TAB ══════════ --}}
        <div class="tab-pane fade p-4" id="bill-pane">
            <h5 class="mb-4">Pay Utility Bill</h5>
            @php $billAccounts = Auth::user()->accounts()->where('status','active')->get(); @endphp
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 bg-light p-4">
                        <form action="{{ route('services.payments.bill') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-medium">Pay From Account</label>
                                <select name="account_id" class="form-select" required>
                                    @foreach($billAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->account_number }} — ৳{{ number_format($acc->balance, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Bill Category</label>
                                <select name="bill_category_id" class="form-select" required>
                                    @foreach(\App\Models\BillCategory::where('is_active',true)->get() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Bill / Account Reference</label>
                                <input type="text" name="payee_reference" class="form-control" placeholder="e.g. Meter No / Customer ID" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Amount (BDT)</label>
                                <input type="number" name="amount" class="form-control" min="1" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-brand w-100">Pay Now <i class="bi bi-arrow-right ms-2"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile recharge tab: pick operator, enter number, select fixed amount --}}
        {{-- ══════════ MOBILE RECHARGE TAB ══════════ --}}
        <div class="tab-pane fade p-4" id="recharge-pane">
            <h5 class="mb-4">Mobile Recharge</h5>
            @php $rechargeAccounts = Auth::user()->accounts()->where('status','active')->get(); @endphp
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 bg-light p-4">
                        <form action="{{ route('services.payments.recharge') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-medium">Pay From Account</label>
                                <select name="account_id" class="form-select" required>
                                    @foreach($rechargeAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->account_number }} — ৳{{ number_format($acc->balance, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Operator</label>
                                <select name="operator" class="form-select" required>
                                    <option value="Grameenphone">Grameenphone (GP)</option>
                                    <option value="Robi">Robi</option>
                                    <option value="Banglalink">Banglalink</option>
                                    <option value="Teletalk">Teletalk</option>
                                    <option value="Airtel">Airtel</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Recharge Amount (BDT)</label>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach([10,20,50,100,200,300,500] as $amt)
                                        <button type="button" class="btn btn-sm btn-outline-secondary amount-btn" data-amount="{{ $amt }}">৳{{ $amt }}</button>
                                    @endforeach
                                </div>
                                <select name="amount" class="form-select" id="rechargeAmount" required>
                                    @foreach([10,20,50,100,200,300,500] as $amt)
                                        <option value="{{ $amt }}">৳{{ $amt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-brand w-100">Recharge Now <i class="bi bi-phone ms-2"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end tab-content --}}
</div>

{{-- ══ MODALS ══ --}}

{{-- Request Card --}}
<div class="modal fade" id="requestCardModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('services.cards.request') }}" method="POST">
            @csrf
            <div class="modal-header border-0"><h5 class="modal-title">Request New Card</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Link to Account</label>
                    <select name="account_id" class="form-select" required>
                        @foreach(Auth::user()->accounts()->where('status','active')->get() as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->account_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Card Type</label>
                    <select name="card_type" class="form-select" required>
                        <option value="debit">Debit Card</option>
                        <option value="credit">Credit Card</option>
                        <option value="prepaid">Prepaid Card</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-brand">Submit Request</button>
            </div>
        </form>
    </div></div>
</div>

{{-- Apply Loan --}}
<div class="modal fade" id="applyLoanModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('services.loans.apply') }}" method="POST">
            @csrf
            <div class="modal-header border-0"><h5 class="modal-title">Apply for Loan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Loan Product</label>
                    <select name="loan_product_id" class="form-select" required>
                        @foreach(\App\Models\LoanProduct::where('is_active',true)->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} — {{ $p->interest_rate }}% p.a.</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Credit to Account</label>
                    <select name="account_id" class="form-select" required>
                        @foreach(Auth::user()->accounts()->where('status','active')->get() as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->account_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Principal Amount (BDT)</label>
                        <input type="number" name="principal" class="form-control" min="1000" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tenure (Months)</label>
                        <input type="number" name="tenure_months" class="form-control" min="1" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-brand">Submit Application</button>
            </div>
        </form>
    </div></div>
</div>

{{-- Open Savings Plan --}}
<div class="modal fade" id="savingsModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('services.savings.store') }}" method="POST">
            @csrf
            <div class="modal-header border-0"><h5 class="modal-title">Open Savings Plan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Plan Type</label>
                    <select name="plan_type" class="form-select" required>
                        <option value="dps">DPS — Monthly Deposit Scheme (6% p.a.)</option>
                        <option value="fdr">FDR — Fixed Deposit Receipt (7.5% p.a.)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Debit From Account</label>
                    <select name="account_id" class="form-select" required>
                        @foreach(Auth::user()->accounts()->where('status','active')->get() as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->account_number }} — ৳{{ number_format($acc->balance, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Deposit Amount (BDT)</label>
                        <input type="number" name="deposit_amount" class="form-control" min="500" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tenure (Months)</label>
                        <input type="number" name="tenure_months" class="form-control" min="3" max="120" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-brand">Open Plan</button>
            </div>
        </form>
    </div></div>
</div>

<script>
// Quick-select recharge amount buttons
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('rechargeAmount').value = btn.dataset.amount;
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('btn-primary','text-white'));
        btn.classList.add('btn-primary','text-white');
    });
});
</script>
@endsection
