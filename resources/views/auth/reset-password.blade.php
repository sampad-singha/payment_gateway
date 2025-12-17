<!doctype html>
<html>
<body>
<h1>Reset Password</h1>

<form method="POST" action="/reset-password">
    @csrf

    <input type="hidden" name="token" value="{{ request()->route('token') }}">

    <input
            type="email"
            name="email"
            placeholder="Email"
            required
    >

    <input
            type="password"
            name="password"
            placeholder="New Password"
            required
    >

    <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm Password"
            required
    >

    <button type="submit">
        Reset Password
    </button>
</form>
</body>
</html>
