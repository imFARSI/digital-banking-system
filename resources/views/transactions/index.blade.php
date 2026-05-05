@extends('layouts.app')

@section('page_title', 'Transactions')

{{-- Page: Transfer/Deposit/Withdraw action cards + full paginated transaction history table --}}
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-send fs-2 text-primary mb-2"></i>
            <h6>Transfer</h6>
            <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#transferModal">Send Money</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-plus-circle fs-2 text-success mb-2"></i>
            <h6>Deposit</h6>
            <button class="btn btn-sm btn-outline-success mt-2" data-bs-toggle="modal" data-bs-target="#depositModal">Deposit Funds</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-dash-circle fs-2 text-danger mb-2"></i>
            <h6>Withdraw</h6>
            <button class="btn btn-sm btn-outline-danger mt-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">Withdraw Cash</button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0">Transaction History</h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Reference</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Detect debit vs credit by checking if sender account belongs to current user --}}
                    @php $userAccountIds = Auth::user()->accounts->pluck('id')->toArray(); @endphp
                    @forelse($transactions as $txn)
                        @php $isDebit = in_array($txn->sender_account_id, $userAccountIds); @endphp
                        <tr>
                            <td class="ps-4 font-monospace text-muted small">{{ $txn->reference_code }}</td>
                            <td>
                                <div class="fw-medium small">{{ $txn->created_at->format('d M Y') }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $txn->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="fw-medium">{{ $txn->description ?? ucfirst($txn->type) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_',' ',$txn->type)) }}</span></td>
                            <td>
                                @if($txn->status == 'completed')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Completed</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">{{ ucfirst($txn->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 fw-bold {{ $isDebit ? 'text-danger' : 'text-success' }}">
                                {{ $isDebit ? '-' : '+' }} ৳{{ number_format($txn->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Transfer modal: enter sender account, recipient account number, amount, description --}}
{{-- Transfer Modal --}}
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transactions.transfer') }}" method="POST">
                @csrf
                <div class="modal-header border-0"><h5 class="modal-title">Transfer Funds</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">From Account</label>
                        <select name="sender_account_id" class="form-select" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_number }} — ৳{{ number_format($acc->balance, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Recipient Account Number</label>
                        <input type="text" name="receiver_account_no" class="form-control" placeholder="e.g. FNX1234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (BDT)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small class="text-muted">(optional)</small></label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand">Confirm Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Deposit modal: select account and enter amount (min ৳10) --}}
{{-- Deposit Modal --}}
<div class="modal fade" id="depositModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transactions.deposit') }}" method="POST">
                @csrf
                <div class="modal-header border-0"><h5 class="modal-title">Deposit Funds</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">To Account</label>
                        <select name="account_id" class="form-select" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_number }} — ৳{{ number_format($acc->balance, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (BDT)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Withdraw modal: select account and enter amount; balance check done server-side --}}
{{-- Withdraw Modal --}}
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transactions.withdraw') }}" method="POST">
                @csrf
                <div class="modal-header border-0"><h5 class="modal-title">Withdraw Cash</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">From Account</label>
                        <select name="account_id" class="form-select" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_number }} — ৳{{ number_format($acc->balance, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (BDT)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small class="text-muted">(optional)</small></label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. ATM withdrawal">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Withdrawal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
