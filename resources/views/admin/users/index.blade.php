@extends('layouts.app')

@section('page_title', 'Manage Users')

{{-- Page: Admin view listing all registered users with search, pagination, and status toggle/delete actions --}}
@section('content')

{{-- Header row --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">All Customers</h5>
        <p class="text-muted small mb-0">View, search and manage all registered customer accounts.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-brand">
        <i class="bi bi-person-plus me-1"></i> Add User Manually
    </a>
</div>

{{-- Search Bar --}}
<form method="GET" class="mb-4">
    <div class="input-group" style="max-width:380px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name or email…" value="{{ $search ?? '' }}">
        <button class="btn btn-brand" type="submit"><i class="bi bi-search"></i></button>
        @if($search)
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Clear</a>
        @endif
    </div>
</form>

{{-- Users Table --}}
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-muted small">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                 style="width:34px;height:34px;font-size:0.85rem;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <a href="{{ route('admin.users.show', $user) }}" class="fw-medium text-decoration-none text-brand">
                                {{ $user->name }}
                            </a>
                        </div>
                    </td>
                    <td class="small text-muted">{{ $user->email }}</td>
                    <td class="small">{{ $user->phone }}</td>
                    <td class="small text-capitalize">{{ $user->gender }}</td>
                    <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                    onclick="return confirm('Are you sure you want to change this users status?')">
                                {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline ms-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('DANGER: This will permanently delete user {{ $user->name }} and ALL their accounts, transactions, and data. This cannot be undone. Proceed?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        No customers found{{ $search ? ' for "' . $search . '"' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-end">
    {{ $users->withQueryString()->links() }}
</div>

@endsection
