<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Activation</title>
</head>

<body>
  <h1>Hello, {{ $user->name }}</h1>
  <p>We received a request to update your email address for your account.</p>
  <p>Please click the button below to verify your new email address:</p>
  <a href="{{ $activationLink }}"
    style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 4px;">
    Activate Email
  </a>
  <p>If you did not request this, please ignore this email.</p>
  <p>Thank you!</p>
</body>

</html>
