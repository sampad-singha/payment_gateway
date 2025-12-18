<!doctype html>
<html>
<body>
<h1>User Dashboard</h1>

<p>Welcome, {{ auth()->user()->email }}</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>
</body>
</html>