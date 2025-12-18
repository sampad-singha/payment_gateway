<!doctype html>
<html>
<body>
<h1>Admin Login</h1>

<form method="POST" action="/login">
    @csrf

    <input
            type="email"
            name="email"
            placeholder="Email"
            required
    >

    <input
            type="password"
            name="password"
            placeholder="Password"
            required
    >

    <button type="submit">Admin Login</button>
</form>
</body>
</html>
