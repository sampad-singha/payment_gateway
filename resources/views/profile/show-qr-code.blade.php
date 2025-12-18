<!doctype html>
<html>
<body>
<h1>Scan QR Code</h1>

{!! auth()->user()->twoFactorQrCodeSvg() !!}
</body>
</html>