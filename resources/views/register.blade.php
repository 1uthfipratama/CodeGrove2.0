@extends('template')

@section('title', 'Register')

@section('content')
<div class="cg-auth-wrapper">
    <div class="cg-auth-card">
        <h2>Create your account</h2>
        <p>Join the CodeGrove community and start collaborating.</p>
        <form action="/register" method="post" class="cg-form">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="cg-input" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="cg-input" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="cg-input" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="cg-input" name="password_confirmation" required>
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
