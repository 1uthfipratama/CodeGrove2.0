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
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input type="password" class="cg-input" name="password" required data-toggle-password>
                <button type="button" class="btn btn-link cg-toggle-password" aria-label="Show password" data-toggle-password-btn>Show</button>
                @error('password')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="cg-input" name="password_confirmation" required data-toggle-password-confirm>
                <button type="button" class="btn btn-link cg-toggle-password cg-toggle-password-confirm" aria-label="Show password" data-toggle-password-confirm-btn>Show</button>
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
<script>
    (function () {
        var btn = document.querySelector('[data-toggle-password-btn]');
        var input = document.querySelector('[data-toggle-password]');
        if (btn && input) {
            btn.addEventListener('click', function () {
                var isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                btn.textContent = isHidden ? 'Hide' : 'Show';
                btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            });
        }
        var confirmBtn = document.querySelector('[data-toggle-password-confirm-btn]');
        var confirmInput = document.querySelector('[data-toggle-password-confirm]');
        if (confirmBtn && confirmInput) {
            confirmBtn.addEventListener('click', function () {
                var isHidden = confirmInput.type === 'password';
                confirmInput.type = isHidden ? 'text' : 'password';
                confirmBtn.textContent = isHidden ? 'Hide' : 'Show';
                confirmBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            });
        }
    })();
</script>
<style>
    .cg-toggle-password {
        position: absolute;
        right: 12px;
        top: 38px;
        padding: 0;
        font-size: 0.85rem;
        text-decoration: none;
    }
    .cg-toggle-password-confirm {
        top: 38px;
    }
</style>
@endsection
