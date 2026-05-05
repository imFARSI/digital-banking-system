@extends('layouts.app')

@section('page_title', 'Accounts & Profile')

{{-- Page: List of user's bank accounts + profile summary sidebar --}}
@section('content')
<div class="row g-4">

    {{-- Profile Card --}}
{{-- Profile sidebar: shows user avatar, contact info, and edit profile link --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center pt-5">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3"
                     style="width:80px;height:80px;font-size:2rem;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                <p class="text-muted mb-0 small">Customer ID: CUST-{{ str_pad(Auth::id(), 5, '0', STR_PAD_LEFT) }}</p>
                <span class="badge bg-success mt-2">{{ ucfirst(Auth::user()->status) }}</span>

                <ul class="list-group list-group-flush text-start mt-4">
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-envelope me-2"></i>Email</span>
                        <span class="fw-medium small">{{ Auth::user()->email }}</span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-telephone me-2"></i>Phone</span>
                        <span class="fw-medium small">{{ Auth::user()->phone }}</span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-calendar me-2"></i>DOB</span>
                        <span class="fw-medium small">{{ Auth::user()->date_of_birth->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-person-vcard me-2"></i>NID</span>
                        <span class="fw-medium small">{{ Auth::user()->nid }}</span>
                    </li>
                </ul>

                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 mt-4">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    {{-- Accounts Table --}}
    {{-- Accounts table: lists all accounts with status badge and close-request button --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Bank Accounts</h5>
                <a href="{{ route('accounts.create') }}" class="btn btn-brand btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Open New Account
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Account Number</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th class="text-end">Balance</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop through each account and render a row --}}
                            @foreach($accounts as $acc)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('accounts.show', $acc) }}" class="fw-medium text-decoration-none">
                                            {{ $acc->account_number }}
                                        </a>
                                    </td>
                                    <td>{{ ucfirst($acc->account_type) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $acc->status=='active' ? 'success' : ($acc->status=='frozen' ? 'warning text-dark' : 'secondary') }}">
                                            {{ ucfirst($acc->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold fs-6">৳{{ number_format($acc->balance, 2) }}</td>
                                    <td class="text-end pe-4">
                                        {{-- Only show close button if account is active; show pending message if frozen --}}
                                        @if($acc->status === 'active')
                                            <form action="{{ route('accounts.close', $acc) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Request to close account {{ $acc->account_number }}? Balance must be zero.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Close Request</button>
                                            </form>
                                        @elseif($acc->status === 'frozen')
                                            <span class="text-muted small">Pending admin review</span>
                                        @endif
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
