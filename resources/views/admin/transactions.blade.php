@extends('layouts.app')

@section('page_title', 'Global Platform Transactions')

{{-- Page: Admin view of all system-wide transactions with volume KPI cards and full table --}}
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white" style="background: linear-gradient(135deg, #0A2540 0%, #1a4b77 100%);">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50">Lifetime Transactions</h6>
                <h2 class="fw-bold">৳{{ number_format($totalVolume, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-brand-accent text-white" style="background: linear-gradient(135deg, #00d4ff 0%, #0083b0 100%);">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50">Total Transaction Count</h6>
                <h2 class="fw-bold">{{ number_format($totalTxns) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">All Transactions History</h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Reference</th>
                        <th>Type</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th class="text-end pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Show sender/receiver as 'SYSTEM/EXTERNAL' if account is null --}}
                    @forelse($transactions as $txn)
                    <tr>
                        <td class="ps-4 font-monospace small fw-bold text-brand">{{ $txn->reference_code }}</td>
                        <td>
                            <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $txn->type) }}</span>
                        </td>
                        <td>
                            @if($txn->senderAccount)
                                <div class="fw-medium">{{ $txn->senderAccount->user->name }}</div>
                                <div class="text-muted small">{{ $txn->senderAccount->account_number }}</div>
                            @else
                                <span class="text-muted">SYSTEM / EXTERNAL</span>
                            @endif
                        </td>
                        <td>
                            @if($txn->receiverAccount)
                                <div class="fw-medium">{{ $txn->receiverAccount->user->name }}</div>
                                <div class="text-muted small">{{ $txn->receiverAccount->account_number }}</div>
                            @else
                                <span class="text-muted">SYSTEM / EXTERNAL</span>
                            @endif
                        </td>
                        <td class="fw-bold">৳{{ number_format($txn->amount, 2) }}</td>
                        <td class="text-end pe-4 text-muted small">
                            {{ $txn->created_at->format('d M Y') }}<br>
                            {{ $txn->created_at->format('h:i A') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5">No transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endsection
