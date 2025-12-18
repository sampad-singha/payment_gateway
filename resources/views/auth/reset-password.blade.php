@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Enter and confirm your new password</p>
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

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Required reset token --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Required email --}}
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

            <div class="form-group">
                <label>New Password</label>
                <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autofocus
                >
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input
                        type="password"
                        name="password_confirmation"
                        placeholder="••••••••"
                        required
                >
            </div>

            <button type="submit" class="primary-btn">
                Reset Password
            </button>
        </form>
    </div>
@endsection