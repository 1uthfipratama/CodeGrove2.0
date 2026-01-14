@extends('template')

@section('title', 'Login')

@section('content')
<div class="cg-auth-wrapper">
    <div class="cg-auth-card">
        <h2>Welcome back</h2>
        <p>Sign in to continue your CodeGrove journey.</p>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="/login" method="post" class="cg-form">
            @csrf
            <div class="mb-3 position-relative">
                <label class="form-label">Username</label>
                <input
                    type="text"
                    class="cg-input"
                    name="username"
                    placeholder="your_username"
                    value="{{ old('username') }}"
                    required
                >
                @error('username')
                    <div class="cg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input type="password" class="cg-input" name="password" placeholder="********" required data-toggle-password>
                <button type="button" class="btn btn-link cg-toggle-password" aria-label="Show password" data-toggle-password-btn>Show</button>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button class="cg-btn-primary w-100" type="submit">Login</button>
        </form>
        <div class="text-center mt-3">
            <span class="text-muted">Don't have an account?</span>
            <a href="/register" class="cg-link">Register</a>
        </div>
    </div>
</div>
<script>
    (function () {
        var btn = document.querySelector('[data-toggle-password-btn]');
        var input = document.querySelector('[data-toggle-password]');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.textContent = isHidden ? 'Hide' : 'Show';
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
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
</style>
@endsection
