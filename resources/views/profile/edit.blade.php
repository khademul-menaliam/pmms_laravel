@extends('layouts.app')

@section('title', 'Profile | PMMS')
@section('page-title', 'Profile management')

@section('content')
    <section class="hero-card">
        <div class="hero-copy">
            <p class="eyebrow">Account</p>
            <h2>Keep your identity, currency, and password up to date.</h2>
            <p>Your finance data stays isolated to this signed-in account.</p>
        </div>

        <div class="mini-stats">
            <div class="mini-card">
                <span class="muted">Signed in as</span>
                <strong>{{ $user->name }}</strong>
            </div>
            <div class="mini-card">
                <span class="muted">Primary currency</span>
                <strong>{{ strtoupper($user->currency) }}</strong>
            </div>
        </div>
    </section>

    <div class="two-column profile-columns">
        <section class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Profile</p>
                    <h3>Basic information</h3>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="form-grid">
                @csrf
                @method('PUT')

                <label class="field">
                    <span>Name</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </label>

                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </label>

                <label class="field">
                    <span>Phone</span>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Optional phone number">
                </label>

                <label class="field">
                    <span>Currency</span>
                    <input type="text" name="currency" value="{{ old('currency', $user->currency) }}" required>
                </label>

                <div class="field field-full">
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </div>
            </form>
        </section>

        <section class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Security</p>
                    <h3>Change password</h3>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="form-grid">
                @csrf
                @method('PUT')

                <label class="field field-full">
                    <span>Current Password</span>
                    <input type="password" name="current_password" required>
                </label>

                <label class="field">
                    <span>New Password</span>
                    <input type="password" name="password" required>
                </label>

                <label class="field">
                    <span>Confirm New Password</span>
                    <input type="password" name="password_confirmation" required>
                </label>

                <div class="field field-full">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </section>
    </div>
@endsection
