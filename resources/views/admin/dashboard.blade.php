<!doctype html>
<html>
<body>
<h1>Admin Dashboard</h1>

<p>
    Welcome, {{ auth()->user()->name }}
    ({{ auth()->user()->getRoleNames()->first() }})
</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>
</body>
</html>
