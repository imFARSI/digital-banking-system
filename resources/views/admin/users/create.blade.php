@extends('layouts.app')

@section('page_title', 'Create User Manually')

{{-- Page: Form for admins to manually register a new customer and auto-provision their first account --}}
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Add New Customer</h5>
                <p class="text-muted small mt-1 mb-0">Create a customer account manually. A savings account will be auto-created.</p>
            </div>
            <div class="card-body px-4 pb-4">

                @if($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Gmail Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required placeholder="user@gmail.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">National ID <span class="text-danger">*</span></label>
                            <input type="text" name="nid" class="form-control @error('nid') is-invalid @enderror"
                                   value="{{ old('nid') }}" required>
                            @error('nid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                   value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">— Select —</option>
                                <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-medium">Temporary Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="adminPass" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required placeholder="Min 8 characters">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePass('adminPass','adminEye')">
                                    <i class="bi bi-eye" id="adminEye"></i>
                                </button>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-brand px-4">
                            <i class="bi bi-person-check me-1"></i> Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePass(fieldId, iconId) {
        const f = document.getElementById(fieldId);
        const i = document.getElementById(iconId);
        f.type = f.type === 'password' ? 'text' : 'password';
        i.className = f.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }
</script>
@endsection
