@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Set a password to secure your account</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SET PASSWORD --}}
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button class="primary-btn">
                Save Password
            </button>
        </form>

        {{-- SKIP FOR LATER --}}
        <div style="margin-top: 1rem; text-align: center;">
            <a href="{{ route('dashboard') }}" class="secondary-btn">
                Skip for now
            </a>
        </div>

        <p class="switch-link">
            You can set a password later from your account settings.
        </p>
    </div>
@endsection
