@extends('layouts.app')

@section('page_title', 'Manage Cards & Accounts')

{{-- Page: Admin panel for managing all accounts (freeze/activate/closure) and cards (freeze/activate) --}}
@section('content')
<div class="row g-4">
    {{-- Closure Requests --}}
    @if($closureRequests->count() > 0)
    <div class="col-12">
        <div class="card border-0 shadow-sm border-start border-danger border-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="text-danger fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Account Closure Requests</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Account Number</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Balance</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($closureRequests as $req)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $req->account_number }}</td>
                                <td>{{ $req->user->name }}</td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($req->account_type) }}</span></td>
                                <td class="fw-bold">৳{{ number_format($req->balance, 2) }}</td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.accounts.approve_closure', $req) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this account? This action cannot be undone.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger px-3">
                                            <i class="bi bi-trash me-1"></i> Approve Closure
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- All Accounts section: admin can toggle status or approve closure --}}
    {{-- All Accounts --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0"><i class="bi bi-wallet2 me-2 text-primary"></i>All Platform Accounts</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Account Number</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Currency</th>
                                <th>Balance</th>
                                <th>Opened On</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $acc)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $acc->account_number }}</td>
                                <td>{{ $acc->user->name }}</td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($acc->account_type) }}</span></td>
                                <td>{{ $acc->currency }}</td>
                                <td class="fw-bold">৳{{ number_format($acc->balance, 2) }}</td>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($acc->opened_at)->format('d M Y') }}</td>
                                <td>
                                    @php $statusColors = ['active' => 'success', 'frozen' => 'warning', 'closed' => 'danger']; @endphp
                                    <span class="badge bg-{{ $statusColors[$acc->status] ?? 'secondary' }} bg-opacity-10 text-{{ $statusColors[$acc->status] ?? 'secondary' }} px-3 py-1 rounded-pill">
                                        {{ ucfirst($acc->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.accounts.toggle', $acc) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $acc->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }} px-3 py-1">
                                            {{ $acc->status === 'active' ? 'Freeze' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- All Cards section: admin can freeze or reactivate any card --}}
    {{-- All Cards --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2 text-brand"></i>All Issued Cards</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Card Number</th>
                                <th>Card Holder</th>
                                <th>Linked Account</th>
                                <th>Type</th>
                                <th>Expiry</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cards as $card)
                            <tr>
                                <td class="ps-4 font-monospace fw-bold text-brand">
                                    {{ chunk_split($card->card_number, 4, ' ') }}
                                </td>
                                <td>{{ $card->cardholder_name }}</td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $card->account->user->name }}</div>
                                    <div class="text-muted small">AC: {{ $card->account->account_number }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($card->card_type) }}</span></td>
                                <td>{{ str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ substr($card->expiry_year, -2) }}</td>
                                <td>
                                    @if($card->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill">Blocked</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.cards.toggle', $card) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $card->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} px-3 py-1">
                                            {{ $card->status === 'active' ? 'Freeze' : 'Activate' }}
                                        </button>
                                    </form>
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
@endsection
