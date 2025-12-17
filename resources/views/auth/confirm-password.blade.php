<!doctype html>
<html>
<body>
<h1>Confirm Password</h1>

<form method="POST" action="/user/confirm-password">
    @csrf

    <input
            type="password"
            name="password"
            placeholder="Password"
            required
    >

    <button type="submit">
        Confirm
    </button>
</form>
</body>
</html>
