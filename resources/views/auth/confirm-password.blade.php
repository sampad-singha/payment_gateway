@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Please confirm your password to continue</p>
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

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label>Password</label>
                <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autofocus
                >
            </div>

            <button type="submit" class="primary-btn">
                Confirm
            </button>
        </form>
    </div>
@endsection
