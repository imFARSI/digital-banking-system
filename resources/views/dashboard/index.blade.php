@extends('layouts.app')

@section('page_title', 'Dashboard')

{{-- Page: Customer dashboard with KPI cards, account list, and recent transactions --}}
@section('content')
    {{-- KPI row: total balance, active loans, reward points, open tickets --}}
    <!-- Top KPI Row -->
    <div class="row g-4 mb-4">
        <!-- Total Balance -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, var(--brand-blue) 0%, #1a4b77 100%); border-radius: 16px;">
                <div class="card-body text-white position-relative overflow-hidden">
                    <div style="position: relative; z-index: 2;">
                        <h6 class="text-white-50 mb-2">Total Balance</h6>
                        <h2 class="mb-0 fw-bold">৳{{ number_format($totalBalance, 2) }}</h2>
                    </div>
                    <i class="bi bi-wallet2 text-white opacity-10" style="font-size: 5rem; position: absolute; right: -10px; bottom: -20px; z-index: 1;"></i>
                </div>
            </div>
        </div>
        
        <!-- Active Loans -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; transition: transform 0.2s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Active Loans</h6>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-cash-coin fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 text-dark fw-bold">{{ $activeLoans }}</h3>
                </div>
            </div>
        </div>

        <!-- Reward Points -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; transition: transform 0.2s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Reward Points</h6>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-star-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 fw-bold" style="color: #ff9f43;">{{ number_format($rewardPoints) }} <span class="fs-6 text-muted fw-normal">pts</span></h3>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; transition: transform 0.2s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Open Tickets</h6>
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-headset fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 text-dark fw-bold">{{ $openTickets }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left column: accounts summary list --}}
        <!-- Accounts Summary -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">My Accounts</h5>
                    <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body px-4">
                    @forelse($accounts as $account)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom {{ $loop->last ? 'border-0 pb-0' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="bi bi-bank fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ ucfirst($account->account_type) }} Account</div>
                                    <div class="text-muted small">AC: ••••{{ substr($account->account_number, -4) }}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-dark">৳{{ number_format($account->balance, 2) }}</div>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size:0.7rem;">Active</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                                <i class="bi bi-wallet2 fs-2 text-muted"></i>
                            </div>
                            <h6 class="text-dark fw-bold">No active accounts</h6>
                            <p class="text-muted small mb-3">Open a new account to start banking.</p>
                            <a href="{{ route('accounts.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">Open Account</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right column: last 10 completed transactions with debit/credit indicator --}}
        <!-- Recent Transactions -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Transactions</h5>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body p-0 mt-2">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-muted fw-medium small border-0">Transaction</th>
                                    <th class="text-muted fw-medium small border-0">Date</th>
                                    <th class="text-end pe-4 text-muted fw-medium small border-0">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Determine debit vs credit based on sender account ownership --}}
                                @forelse($recentTransactions as $txn)
                                    @php
                                        $isDebit = in_array($txn->sender_account_id, $accounts->pluck('id')->toArray());
                                        $icon = $isDebit ? 'bi-arrow-up-right text-danger' : 'bi-arrow-down-left text-success';
                                        $bg = $isDebit ? 'bg-danger' : 'bg-success';
                                    @endphp
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('transactions.index') }}'">
                                        <td class="ps-4 border-0 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="{{ $bg }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi {{ $icon }}"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $txn->description ?? ucfirst($txn->type) }}</div>
                                                    <div class="text-muted small" style="font-size: 0.75rem;">Ref: {{ $txn->reference_code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small border-0">{{ $txn->created_at->format('d M, g:i A') }}</td>
                                        <td class="text-end pe-4 border-0">
                                            <div class="fw-bold {{ $isDebit ? 'text-dark' : 'text-success' }}">
                                                {{ $isDebit ? '-' : '+' }}৳{{ number_format($txn->amount, 2) }}
                                            </div>
                                            @if($txn->status === 'completed')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size:0.65rem;">Completed</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill" style="font-size:0.65rem;">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 border-0">
                                            <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                                                <i class="bi bi-receipt fs-2 text-muted"></i>
                                            </div>
                                            <h6 class="text-dark fw-bold">No transactions yet</h6>
                                            <p class="text-muted small mb-0">Your recent activities will appear here.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
