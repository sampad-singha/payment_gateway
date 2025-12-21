@extends('layout.frontend.auth')

@section('content')
    <div class="auth-card">
        <div class="brand">
            <h1>LearnEdge</h1>
            <p>Two-Factor Authentication (2FA)</p>
        </div>

        {{-- 1️⃣ NOT ENABLED --}}
        @if (! auth()->user()->two_factor_secret)

            <p>Add extra security to your account using an authenticator app.</p>

            <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                @csrf
                <button type="submit" class="primary-btn">
                    Enable 2FA
                </button>
            </form>

            {{-- 2️⃣ ENABLED BUT NOT CONFIRMED --}}
        @elseif (! auth()->user()->two_factor_confirmed_at)

            <p><strong>Finish setting up two-factor authentication.</strong></p>

            <div class="qr-section">
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                <p>Scan this QR code with Google Authenticator or Authy.</p>
            </div>

            <div class="backup-codes">
                <p><strong>Recovery Codes</strong> (save these somewhere safe):</p>
                <ul>
                    @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- CONFIRM 2FA --}}
            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                @csrf

                <div class="form-group">
                    <label>Enter the 6-digit code from your app</label>
                    <input
                            type="text"
                            name="code"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            placeholder="123456"
                            required
                    >
                </div>

                <button type="submit" class="primary-btn">
                    Confirm 2FA
                </button>
            </form>

            {{-- 3️⃣ ENABLED & CONFIRMED --}}
        @else

            <p><strong>Two-factor authentication is enabled.</strong></p>

            <div class="backup-codes">
                <p>Recovery codes:</p>
                <ul>
                    @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- DISABLE 2FA --}}
            <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                @csrf
                @method('DELETE')

                <button class="primary-btn danger">
                    Disable 2FA
                </button>
            </form>

        @endif
    </div>
@endsection