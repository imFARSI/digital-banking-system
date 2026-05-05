@extends('layouts.app')

@section('page_title', 'User Details: ' . $user->name)

{{-- Page: Detailed 360-degree view of a specific user including their accounts, loans, and recent transactions for admins --}}
@section('content')
<div class="row g-4">
    {{-- User Profile Card --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mx-auto mb-3"
                     style="width:80px;height:80px;font-size:2.5rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                
                <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill mb-4">
                    {{ ucfirst($user->status) }} Account
                </span>

                <div class="row text-start g-3 mt-2 border-top pt-4">
                    <div class="col-6">
                        <small class="text-muted d-block">Phone</small>
                        <span class="fw-medium small">{{ $user->phone }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Gender</small>
                        <span class="fw-medium small text-capitalize">{{ $user->gender }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">National ID</small>
                        <span class="fw-medium small">{{ $user->nid }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Joined</small>
                        <span class="fw-medium small">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top">
                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-grid gap-2">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                            <i class="bi bi-shield-exclamation me-1"></i> {{ $user->status === 'active' ? 'Suspend Account' : 'Activate Account' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial Overview --}}
    <div class="col-md-8">
        <div class="row g-4 h-100">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-primary text-white h-100" style="background: linear-gradient(135deg, #0A2540 0%, #1a4b77 100%);">
                    <div class="card-body p-4">
                        <h6 class="text-white-50 mb-1">Total Liquid Balance</h6>
                        <h2 class="fw-bold mb-0">৳{{ number_format($totalBalance, 2) }}</h2>
                        <i class="bi bi-wallet2 opacity-10" style="position: absolute; right: 20px; bottom: 10px; font-size: 4rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-brand-accent text-white h-100" style="background: linear-gradient(135deg, #00d4ff 0%, #0083b0 100%);">
                    <div class="card-body p-4">
                        <h6 class="text-white-50 mb-1">Active / Pending Loans</h6>
                        <h2 class="fw-bold mb-0">{{ $activeLoansCount }} / {{ $pendingLoansCount }}</h2>
                        <i class="bi bi-cash-stack opacity-20" style="position: absolute; right: 20px; bottom: 10px; font-size: 4rem;"></i>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom pt-4 pb-2 px-4">
                        <h5 class="mb-0 fw-bold">Linked Accounts</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Account Number</th>
                                        <th>Type</th>
                                        <th>Balance</th>
                                        <th>Cards</th>
                                        <th class="text-end pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->accounts as $acc)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $acc->account_number }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ strtoupper($acc->account_type) }}</span></td>
                                        <td class="fw-bold">৳{{ number_format($acc->balance, 2) }}</td>
                                        <td>{{ $acc->cards->count() }}</td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-{{ $acc->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $acc->status === 'active' ? 'success' : 'secondary' }} px-3 py-1 rounded-pill">
                                                {{ ucfirst($acc->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loan Details --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0 fw-bold">Loan History</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Principal</th>
                                <th>Outstanding</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->loans as $loan)
                            <tr>
                                <td class="ps-4">{{ $loan->loanProduct->name ?? 'Standard' }}</td>
                                <td>৳{{ number_format($loan->principal, 2) }}</td>
                                <td class="fw-bold">৳{{ number_format($loan->outstanding_balance, 2) }}</td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-{{ $loan->status === 'active' ? 'success' : ($loan->status === 'pending' ? 'warning' : 'danger') }} px-2 py-1 rounded">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No loans found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0 fw-bold">Platform Activity</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Type</th>
                                <th>Amount</th>
                                <th class="text-end pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $txn)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium">{{ ucfirst(str_replace('_', ' ', $txn->type)) }}</div>
                                    <div class="text-muted small">Ref: {{ $txn->reference_code }}</div>
                                </td>
                                <td class="fw-bold">৳{{ number_format($txn->amount, 2) }}</td>
                                <td class="text-end pe-4 text-muted small">{{ $txn->created_at->format('d M, H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No recent transactions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
