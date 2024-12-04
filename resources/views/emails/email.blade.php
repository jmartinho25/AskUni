<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Hello, {{ $mailData['username'] }}!</h1>
    <p>Use the link below to reset your password:</p>
    <p>Click <a href="{{ $mailData['resetLink'] }}">here</a> to reset your password.</p>
</body>
</html>