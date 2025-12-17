<!doctype html>
<html>
<body>
<h1>Two Factor Authentication</h1>

{{-- 1️⃣ NOT ENABLED --}}
@if (! auth()->user()->two_factor_secret)
    <form method="POST" action="/user/two-factor-authentication">
        @csrf
        <button>Enable 2FA</button>
    </form>

    {{-- 2️⃣ PENDING CONFIRMATION --}}
@elseif (! auth()->user()->two_factor_confirmed_at)
    <p><strong>Finish setting up two-factor authentication.</strong></p>

    <h3>Scan this QR code with Google Authenticator</h3>
    {!! auth()->user()->twoFactorQrCodeSvg() !!}

    <h3>Recovery Codes</h3>
    <ul>
        @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
            <li>{{ $code }}</li>
        @endforeach
    </ul>

    {{-- CONFIRM --}}
    <form method="POST" action="/user/confirmed-two-factor-authentication">
        @csrf

        <input
                type="text"
                name="code"
                placeholder="6-digit code"
                inputmode="numeric"
                pattern="[0-9]{6}"
                required
        >

        <button>Confirm 2FA</button>
    </form>

    {{-- 3️⃣ CONFIRMED / ENABLED --}}
@else
    <p><strong>Two-factor authentication is enabled.</strong></p>

    <form method="POST" action="/user/two-factor-authentication">
        @csrf
        @method('DELETE')
        <button>Disable 2FA</button>
    </form>
@endif

</body>
</html>
