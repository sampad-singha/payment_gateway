@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Create your account</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="primary-btn">
                Sign Up
            </button>
        </form>

        {{-- OAuth Buttons --}}
        <div class="oauth-buttons">
            <a href="{{ route('social.redirect', 'google') }}" class="oauth-btn">
                <i class="fa-brands fa-google"></i> Continue with Google
            </a>

            <a href="{{ route('social.redirect', 'facebook') }}" class="oauth-btn">
                <i class="fa-brands fa-facebook-f"></i> Continue with Facebook
            </a>
        </div>

        <p class="switch-link">
            Already have an account?
            <a href="{{ route('login') }}">Sign in</a>
        </p>
    </div>
@endsection