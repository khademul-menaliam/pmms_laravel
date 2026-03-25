@extends('layouts.guest')

@section('title', 'Log In | PMMS')
@section('content')
    <div class="section-head">
        <div>
            <p class="eyebrow">Welcome Back</p>
            <h2>Log in to your workspace</h2>
        </div>
        <a href="{{ route('register') }}" class="btn btn-soft">Create Account</a>
    </div>

    <form method="POST" action="{{ route('login.store') }}" class="form-stack">
        @csrf
        <label class="field">
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        </label>

        <label class="field">
            <span>Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
        </label>

        <label class="checkbox-field inline">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <span>Keep me signed in on this device</span>
        </label>

        <button type="submit" class="btn btn-primary auth-submit">Log In</button>
    </form>

    @if (app()->isLocal())
        <div class="auth-note">
            <strong>Demo accounts</strong>
            <p><code>demo@pmms.test</code> / <code>password</code></p>
            <p><code>sadia@pmms.test</code> / <code>password</code></p>
        </div>
    @endif
@endsection
