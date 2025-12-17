<!doctype html>
<html>
<body>
<h1>Recovery Codes</h1>

<ul>
    @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
        <li>{{ $code }}</li>
    @endforeach
</ul>
</body>
</html>