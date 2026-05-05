@extends('layouts.app')

@section('page_title', 'Admin — Loan Management')

@section('content')

{{-- Page: Admin loan management with two sections: pending applications and active loans --}}

{{-- Section 1: Pending loans awaiting admin approve or reject --}}
{{-- Pending Loan Applications --}}
<div class="card border-0 shadow-sm mb-5">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0">
            <span class="badge bg-warning text-dark me-2">{{ $pendingLoans->count() }}</span>
            Pending Loan Applications
        </h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Applicant</th>
                        <th>Loan Type</th>
                        <th>Principal</th>
                        <th>EMI</th>
                        <th>Tenure</th>
                        <th>Applied</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLoans as $loan)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium">{{ $loan->user->name }}</div>
                                <div class="text-muted small">{{ $loan->user->email }}</div>
                            </td>
                            <td>{{ $loan->loanProduct->name ?? '—' }}</td>
                            <td class="fw-bold">৳{{ number_format($loan->principal, 2) }}</td>
                            <td>৳{{ number_format($loan->monthly_installment, 2) }}/mo</td>
                            <td>{{ $loan->tenure_months }} months</td>
                            <td class="text-muted small">{{ $loan->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1"
                                        onclick="return confirm('Approve and disburse ৳{{ number_format($loan->principal,2) }} to {{ $loan->user->name }}?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.loans.reject', $loan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Reject this loan application?')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No pending loan applications.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Section 2: All currently disbursed active loans with outstanding balance tracking --}}
{{-- Active Loans --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0">
            <span class="badge bg-success me-2">{{ $activeLoans->count() }}</span>
            Active Loans
        </h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Customer</th>
                        <th>Principal</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>EMI</th>
                        <th>Maturity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeLoans as $loan)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium">{{ $loan->user->name }}</div>
                                <div class="text-muted small">AC: {{ $loan->account->account_number ?? '—' }}</div>
                            </td>
                            <td>৳{{ number_format($loan->principal, 2) }}</td>
                            <td class="text-success">৳{{ number_format(($loan->principal * (1 + ($loan->interest_rate * $loan->tenure_months / 1200))) - $loan->outstanding_balance, 2) }}</td>
                            <td class="text-danger fw-bold">৳{{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td>৳{{ number_format($loan->monthly_installment, 2) }}/mo</td>
                            <td>{{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : '—' }}</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Active</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No active loans.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
