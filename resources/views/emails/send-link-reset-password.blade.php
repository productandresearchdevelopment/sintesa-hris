<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Password Reset Request</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 100%;
      padding: 20px;
      background-color: #ffffff;
      max-width: 600px;
      margin: 50px auto;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #333;
    }

    p {
      color: #666;
    }

    .button {
      display: inline-block;
      padding: 12px 25px;
      font-size: 16px;
      color: #fff;
      background-color: #007bff;
      text-decoration: none;
      border-radius: 5px;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      color: #999;
      font-size: 12px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Password Reset Request</h2>
    <p>Hello, {{ $personalInfo['name'] }}</p>
    <p>You recently requested to reset your password for your account. Click the button below to reset it.</p>
    <a href="{{ $resetLink }}" class="button">Reset Password</a>
    <p><strong>Note:</strong> This link will expire in 15 minutes.</p>
    <p>If you didn’t request a password reset, please ignore this email or contact support if you have questions.</p>
    <p>Thanks,<br>PT SINTESA TALENTA ASIA</p>

    <div class="footer">
      <p>If you’re having trouble clicking the password reset button, copy and paste the URL below into your web
        browser:</p>
      <p><a href="{{ $resetLink }}">Link to Reset Password</a></p>
    </div>
  </div>
</body>

</html>
