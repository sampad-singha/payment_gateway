@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Welcome back. Please sign in to continue.</p>
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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-footer">
                <label>
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="primary-btn">
                Sign In
            </button>
        </form>

        {{-- OAuth (UI only unless Socialite routes added) --}}
        <div class="oauth-buttons">
            <a href="{{ route('social.redirect', 'google') }}" class="oauth-btn">
                <i class="fa-brands fa-google"></i> Continue with Google
            </a>

            <a href="{{ route('social.redirect', 'facebook') }}" class="oauth-btn">
                <i class="fa-brands fa-facebook-f"></i> Continue with Facebook
            </a>
        </div>

        <p class="switch-link">
            Don’t have an account?
            <a href="{{ route('register') }}">Sign up</a>
        </p>
    </div>
@endsection