@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Enter the authentication code from your app</p>
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

        {{-- AUTHENTICATOR CODE --}}
        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="form-group">
                <label>Authentication Code</label>
                <input
                        type="text"
                        name="code"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        placeholder="123456"
                        autofocus
                        required
                >
            </div>

            <button type="submit" class="primary-btn">
                Verify
            </button>
        </form>

        <hr>

        {{-- RECOVERY CODE OPTION --}}
        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="form-group">
                <label>Recovery Code</label>
                <input
                        type="text"
                        name="recovery_code"
                        placeholder="XXXX-XXXX"
                >
            </div>

            <button type="submit" class="primary-btn secondary">
                Use Recovery Code
            </button>
        </form>

        <p class="switch-link">
            Lost your device? Use a recovery code instead.
        </p>
    </div>
@endsection