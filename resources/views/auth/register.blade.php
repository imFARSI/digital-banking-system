@extends('layouts.auth')

@section('title', 'Register')

{{-- Page: New customer registration form (Gmail-only email, NID required) --}}
@section('content')
<div class="auth-card register-card">
    <div class="brand-header mb-3">
        <a href="/" class="brand-logo"><i class="bi bi-bank2"></i> FINEXA</a>
        <div class="brand-tagline">Create your secure digital banking account</div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #ff6b6b; border-radius: 10px;">
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Registration form submits to POST /register --}}
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Gmail Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">National ID (NID) <span class="text-danger">*</span></label>
                <input type="text" name="nid" class="form-control" value="{{ old('nid') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <select name="gender" class="form-select" required style="color:#fff;">
                    <option value="" style="color:#000;">— Select —</option>
                    <option value="male" style="color:#000;" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" style="color:#000;" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" style="color:#000;" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- Password with show/hide toggle --}}
            <div class="col-md-6">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePass('password','eyeIcon1')">
                        <i class="bi bi-eye" id="eyeIcon1"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePass('password_confirmation','eyeIcon2')">
                        <i class="bi bi-eye" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-4 mb-3">
            Create Account <i class="bi bi-person-plus ms-1"></i>
        </button>

        <p class="text-center text-muted small mb-0">
            Already have an account? <a href="{{ route('login') }}" class="fw-bold">Sign in</a>
        </p>
    </form>
</div>

<script>
    function togglePass(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
@endsection
