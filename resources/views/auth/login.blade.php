@extends('layouts.auth')

@section('title', 'Login')

{{-- Page: Login form; supports both customer email and hardcoded admin username --}}
@section('content')
<div class="auth-card">
    <div class="brand-header">
        <a href="/" class="brand-logo"><i class="bi bi-bank2"></i> FINEXA</a>
        <div class="brand-tagline">Welcome back to Secure Digital Banking</div>
    </div>

    {{-- Show all validation errors returned from AuthController --}}
    @if($errors->any())
        <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #ff6b6b; border-radius: 10px;">
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Login form: submits to POST /login --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email or Username</label>
            <input type="text" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="Enter your email or 'admin'">
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0">Password</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-4">
            Sign In <i class="bi bi-arrow-right ms-1"></i>
        </button>

        <p class="text-center text-muted small mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="fw-bold">Open a Free Account</a>
        </p>
    </form>
</div>

<script>
    function togglePass() {
        const field = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
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
