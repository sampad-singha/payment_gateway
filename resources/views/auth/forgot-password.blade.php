@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Forgot your password? No problem.</p>
        </div>

        {{-- Status Message --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="name@company.com"
                        required
                        autofocus
                >
            </div>

            <button type="submit" class="primary-btn">
                Send Reset Link
            </button>
        </form>

        <p class="switch-link">
            Remember your password?
            <a href="{{ route('login') }}">Sign in</a>
        </p>
    </div>
@endsection