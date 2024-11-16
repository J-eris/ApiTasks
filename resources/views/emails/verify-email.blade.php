<!DOCTYPE html>
<html>

<head>
  <title>Verify Email</title>
</head>

<body>
  <p>Hi {{ $name }},</p>
  <p>Please click the link below to verify your email address and activate your account:</p>
  <a href="{{ $verificationUrl }}">Verify Email</a>
</body>

</html>