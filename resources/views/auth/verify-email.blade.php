<!doctype html>
<html>
<body>
<h1>Verify your email address</h1>

<p>
    A verification link has been sent to your email. Click it to verify your account.
</p>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">Resend verification email</button>
</form>

@if(session('status') === 'verification-link-sent')
    <p>Verification link sent. Check your email.</p>
@endif
</body>
</html>
