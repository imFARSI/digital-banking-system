@extends('layouts.app')

@section('page_title', 'Account Details — ' . $account->account_number)

{{-- Page: Detailed view of a single account with balance card, quick actions, and transaction history --}}
@section('content')
<div class="row mb-4 g-4">
    <!-- Account Card -->
    <div class="col-md-4">
        <div class="card border-0 h-100 text-white" style="background: linear-gradient(135deg, #0A2540 0%, #1a4b77 100%);">
            <div class="card-body p-4">
                <h6 class="text-white-50 mb-1">{{ strtoupper($account->account_type) }} ACCOUNT</h6>
                <h3 class="font-monospace mb-4">{{ $account->account_number }}</h3>
                <h2 class="mb-1">৳{{ number_format($account->balance, 2) }}</h2>
                <small class="text-white-50">Available Balance</small>
                <hr class="border-white border-opacity-25 my-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-white-50 d-block">Status</small>
                        <span class="badge bg-success">{{ ucfirst($account->status) }}</span>
                    </div>
                    <div>
                        <small class="text-white-50 d-block">Currency</small>
                        <span class="fw-medium">{{ $account->currency }}</span>
                    </div>
                    <div>
                        <small class="text-white-50 d-block">Opened</small>
                        <span class="fw-medium">{{ $account->opened_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body d-flex gap-3 align-items-center flex-wrap">
                <button class="btn btn-outline-primary px-4 py-3" data-bs-toggle="modal" data-bs-target="#depositModal">
                    <i class="bi bi-plus-circle d-block fs-2 mb-1"></i>
                    Deposit
                </button>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary px-4 py-3">
                    <i class="bi bi-send d-block fs-2 mb-1"></i>
                    Transfer
                </a>
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-4 py-3">
                    <i class="bi bi-credit-card d-block fs-2 mb-1"></i>
                    Request Card
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Paginated transaction table for this account; amounts colored red=debit, green=credit --}}
<!-- Linked Transactions -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transaction History</h5>
        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Reference</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        @php $isDebit = $txn->sender_account_id == $account->id; @endphp
                        <tr>
                            <td class="ps-4 font-monospace small text-muted">{{ $txn->reference_code }}</td>
                            <td>{{ $txn->created_at->format('d M Y') }}</td>
                            <td>{{ $txn->description ?? ucfirst($txn->type) }}</td>
                            <td>
                                <span class="badge {{ $txn->status == 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($txn->status) }}</span>
                            </td>
                            <td class="text-end pe-4 fw-bold {{ $isDebit ? 'text-danger' : 'text-success' }}">
                                {{ $isDebit ? '-' : '+' }}৳{{ number_format($txn->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No transactions for this account yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Deposit modal: hidden by default, opened by the Deposit quick-action button --}}
<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transactions.deposit') }}" method="POST">
                @csrf
                <input type="hidden" name="account_id" value="{{ $account->id }}">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Deposit to {{ $account->account_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount ({{ $account->currency }})</label>
                        <input type="number" name="amount" class="form-control form-control-lg" step="0.01" min="10" placeholder="0.00" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand">Confirm Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
