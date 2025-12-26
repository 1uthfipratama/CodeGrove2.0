@extends('template')

@section('title', 'Register')

@section('content')
<div class="cg-auth-wrapper">
    <div class="cg-auth-card">
        <h2>Create your account</h2>
        <p>Join the CodeGrove community and start collaborating.</p>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <form action="/register" method="post" class="cg-form">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="cg-input" name="username" value="{{ old('username') }}" required>
                @error('username')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="cg-input" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="cg-input" name="password" required>
                @error('password')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="cg-input" name="password_confirmation" required>
                @error('password_confirmation')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="cg-input" name="dob" value="{{ old('dob') }}" required>
                @error('dob')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <button class="cg-btn-primary w-100" type="submit">Register</button>
        </form>
        <div class="text-center mt-3">
            <span class="text-muted">Already have an account?</span>
            <a href="/login" class="cg-link">Login</a>
        </div>
    </div>
</div>
@endsection
