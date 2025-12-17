@extends('template')

@section('title', 'Login')

@section('content')
<div class="cg-auth-wrapper">
    <div class="cg-auth-card">
        <h2>Welcome back</h2>
        <p>Sign in to continue your CodeGrove journey.</p>
        <form action="/login" method="post" class="cg-form">
            @csrf
            <div class="mb-3 position-relative">
                <label class="form-label">Email</label>
                <input type="email" class="cg-input" name="email" placeholder="you@example.com" required>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input type="password" class="cg-input" name="password" placeholder="********" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="remember">
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
@endsection
