@extends('layouts.app')

@section('page_title', 'My Profile')

{{-- Page: Edit profile form for name, phone, and optional password change --}}
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h5 class="mb-0">Update Profile</h5>
                <p class="text-muted small mb-0">Manage your personal information and password.</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    
                    {{-- Section 1: Editable personal info (email and NID are read-only) --}}
                    <h6 class="text-muted text-uppercase small letter-spacing mb-3">Personal Information</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email cannot be changed.</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">National ID</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->nid }}" disabled>
                        </div>
                    </div>

                    {{-- Section 2: Optional password change; blank fields = no change --}}
                    <hr class="my-4">
                    <h6 class="text-muted text-uppercase small mb-3">Change Password <span class="text-muted fw-normal">(leave blank to keep current)</span></h6>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-brand px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
