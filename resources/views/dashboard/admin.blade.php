@extends('layouts.app')

@section('page_title', 'Admin Dashboard')

{{-- Page: Admin dashboard showing system-wide stats and quick action shortcuts --}}
@section('content')

{{-- KPI row: total customers, system balance, today's transactions, pending actions --}}
<!-- Top KPI Row -->
<div class="row g-4 mb-4">
    <!-- Total Customers -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0A2540 0%, #1a4b77 100%); border-radius: 16px;">
            <div class="card-body text-white position-relative overflow-hidden">
                <div style="position: relative; z-index: 2;">
                    <h6 class="text-white-50 mb-2 fw-medium">Total Customers</h6>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h2>
                </div>
                <i class="bi bi-people-fill text-white opacity-10" style="font-size: 5rem; position: absolute; right: -10px; bottom: -20px; z-index: 1;"></i>
            </div>
        </div>
    </div>
    
    <!-- System Balance -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted mb-0 fw-medium">System Balance</h6>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-bank2 fs-5"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold text-dark">৳{{ number_format($systemBalance, 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Today's Volume -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted mb-0 fw-medium">Today's Transactions</h6>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-arrow-left-right fs-5"></i>
                    </div>
                </div>
                <h3 class="mb-0 fw-bold text-dark">{{ number_format($todayTransactions) }}</h3>
                <p class="text-success small mb-0 fw-medium mt-1"><i class="bi bi-graph-up me-1"></i>৳{{ number_format($todayVolume, 0) }} vol</p>
            </div>
        </div>
    </div>

    <!-- Pending Actions -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted mb-0 fw-medium">Pending Actions</h6>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-exclamation-circle fs-5"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-medium text-dark">Loan Approvals</span>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $pendingLoans }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-medium text-dark">Support Tickets</span>
                        <span class="badge {{ $openTickets > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill">{{ $openTickets }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick action cards: links to Users, Loans, Support, and Transactions management --}}
<!-- Admin Quick Actions -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
        <h5 class="mb-0 fw-bold">Admin Management</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="row g-3">
            
            <div class="col-md-3">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none d-block h-100">
                    <div class="card border-1 h-100 p-3 rounded-4 action-card" style="transition: all 0.2s; border-color: #e2e8f0;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-4 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Manage Users</h6>
                                <small class="text-muted">View and edit customers</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.loans') }}" class="text-decoration-none d-block h-100">
                    <div class="card border-1 h-100 p-3 rounded-4 action-card" style="transition: all 0.2s; border-color: #e2e8f0;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-cash-coin fs-4 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Loan Requests</h6>
                                <small class="text-muted">Approve or reject loans</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.support') }}" class="text-decoration-none d-block h-100">
                    <div class="card border-1 h-100 p-3 rounded-4 action-card" style="transition: all 0.2s; border-color: #e2e8f0;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-headset fs-4 text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Support Tickets</h6>
                                <small class="text-muted">Resolve customer issues</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>



        </div>
    </div>
</div>

<style>
    .action-card:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: var(--brand-accent) !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }
    .action-card {
        background: rgba(255, 255, 255, 0.02) !important;
    }
</style>

@endsection
