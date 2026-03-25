@extends('layouts.guest')

@section('title', 'Register | PMMS')
@section('content')
    <div class="section-head">
        <div>
            <p class="eyebrow">Get Started</p>
            <h2>Create your PMMS account</h2>
        </div>
        <a href="{{ route('login') }}" class="btn btn-soft">Log In</a>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="form-stack">
        @csrf
        <div class="form-grid auth-grid">
            <label class="field">
                <span>Full Name</span>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required>
            </label>

            <label class="field">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            </label>

            <label class="field">
                <span>Phone</span>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Optional phone number">
            </label>

            <label class="field">
                <span>Currency</span>
                <input type="text" name="currency" value="{{ old('currency', 'BDT') }}" placeholder="BDT" required>
            </label>

            <label class="field">
                <span>Password</span>
                <input type="password" name="password" placeholder="Minimum 8 characters" required>
            </label>

            <label class="field">
                <span>Confirm Password</span>
                <input type="password" name="password_confirmation" placeholder="Repeat password" required>
            </label>
        </div>

        <button type="submit" class="btn btn-primary auth-submit">Create Account</button>
    </form>
@endsection
