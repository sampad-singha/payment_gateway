<!doctype html>
<html>
<body>
<h1>Two Factor Challenge</h1>

<form method="POST" action="/two-factor-challenge">
    @csrf

    <input
            type="text"
            name="code"
            placeholder="Authentication Code"
    >

    <button>Verify</button>
</form>

<form method="POST" action="/two-factor-challenge">
    @csrf

    <input
            type="text"
            name="recovery_code"
            placeholder="Recovery Code"
    >

    <button>Use Recovery Code</button>
</form>
</body>
</html>