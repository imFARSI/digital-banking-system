@extends('layouts.app')

@section('page_title', 'Loans & Financing')

{{-- Page: Active loans table with repayment modals; apply-loan modal at bottom --}}
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Active Loans</h5>
        <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#applyLoanModal">
            <i class="bi bi-plus-lg me-1"></i>Apply for Loan
        </button>
    </div>

    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Loan Type</th>
                        <th>Principal</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Monthly EMI</th>
                        <th>Tenure</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $loan->loanProduct->name ?? 'Standard Loan' }}</div>
                                <div class="text-muted small">Status: 
                                    @php $badgeMap = ['pending'=>'warning','active'=>'success','rejected'=>'danger','paid_off'=>'info']; @endphp
                                    <span class="text-{{ $badgeMap[$loan->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $loan->status)) }}</span>
                                </div>
                            </td>
                            <td>৳{{ number_format($loan->principal, 2) }}</td>
                            <td class="text-success">৳{{ number_format(($loan->principal * (1 + ($loan->interest_rate * $loan->tenure_months / 1200))) - $loan->outstanding_balance, 2) }}</td>
                            <td class="fw-bold text-danger">৳{{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="fw-medium text-brand">৳{{ number_format($loan->monthly_installment, 2) }}</td>
                            <td>{{ $loan->tenure_months }} Mo</td>
                            <td class="text-end pe-4">
                                @if($loan->status === 'active' || $loan->status === 'disbursed')
                                    <button class="btn btn-sm btn-brand px-3" data-bs-toggle="modal" data-bs-target="#repayModal{{ $loan->id }}">
                                        Repay
                                    </button>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-cash-stack fs-1 d-block mb-3 opacity-25"></i>
                                No loan applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Generate one repay modal per active loan --}}
@foreach($loans as $loan)
    @if($loan->status === 'active' || $loan->status === 'disbursed')
        {{-- Repay Modal --}}
        <div class="modal fade" id="repayModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('services.loans.repay', $loan) }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Repay Loan #{{ $loan->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Repay From Account</label>
                                <div class="p-2 bg-light rounded text-dark fw-bold">
                                    {{ $loan->account->account_number }}
                                </div>
                                <input type="hidden" name="account_id" value="{{ $loan->account_id }}">
                                <div class="form-text small">Balance: ৳{{ number_format($loan->account->balance, 2) }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium d-flex justify-content-between">
                                    Repayment Amount (BDT)
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" 
                                        onclick="document.getElementById('repayAmt{{ $loan->id }}').value = '{{ $loan->outstanding_balance }}'">
                                        Repay Full (৳{{ number_format($loan->outstanding_balance, 2) }})
                                    </button>
                                </label>
                                <input type="number" name="amount" id="repayAmt{{ $loan->id }}" class="form-control bg-light border-0 py-2" 
                                    value="{{ number_format($loan->monthly_installment, 2, '.', '') }}" 
                                    max="{{ $loan->outstanding_balance }}" min="0.01" step="any" required>
                                <div class="form-text small text-brand">Suggested EMI: ৳{{ number_format($loan->monthly_installment, 2) }}</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-brand px-4">Confirm Repayment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- Apply Loan Modal: pick product, account, principal, and tenure --}}
{{-- Apply Loan Modal --}}
<div class="modal fade" id="applyLoanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('services.loans.apply') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Apply for Loan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Loan Type</label>
                        <select name="loan_product_id" class="form-select bg-light border-0 py-2" required>
                            @foreach($types as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} — {{ $p->interest_rate }}% Interest</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Credit to Account</label>
                        <select name="account_id" class="form-select bg-light border-0 py-2" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Principal Amount (BDT)</label>
                            <input type="number" name="principal" class="form-control bg-light border-0 py-2" min="1000" placeholder="e.g. 50000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tenure (Months)</label>
                            <input type="number" name="tenure_months" class="form-control bg-light border-0 py-2" min="1" placeholder="e.g. 12" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand px-4">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
