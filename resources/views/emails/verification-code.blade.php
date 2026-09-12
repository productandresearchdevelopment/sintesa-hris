<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification Code</title>
</head>

<body
  style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0"
          style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">

          <tr>
            <td
              style="background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%); padding: 40px 30px; text-align: center;">
              <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">Email
                Verification</h1>
            </td>
          </tr>

          <tr>
            <td style="padding: 50px 40px;">
              <h2 style="margin: 0 0 20px 0; color: #2d3748; font-size: 24px; font-weight: 600;">Hello,
                {{ $user->name }}</h2>

              <p style="margin: 0 0 30px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                Thank you for signing up! To complete your registration, please use the verification code below:
              </p>

              <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                <tr>
                  <td align="center">
                    <div
                      style="background: linear-gradient(135deg, #f6f8fb 0%, #e9eef5 100%); border: 2px dashed #cbd5e0; border-radius: 12px; padding: 30px; display: inline-block;">
                      <p
                        style="margin: 0 0 10px 0; color: #718096; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                        Your Verification Code</p>
                      <h1
                        style="margin: 0; color: #2d3748; font-size: 48px; font-weight: 700; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                        {{ $code }}</h1>
                    </div>
                  </td>
                </tr>
              </table>

              <table width="100%" cellpadding="0" cellspacing="0"
                style="background-color: #fff5f5; border-left: 4px solid #fc8181; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                <tr>
                  <td>
                    <p style="margin: 0; color: #742a2a; font-size: 14px; line-height: 1.6;">
                      <strong>Important:</strong> This code will expire in <strong>10 minutes</strong>.
                    </p>
                  </td>
                </tr>
              </table>

              <p style="margin: 0 0 20px 0; color: #4a5568; font-size: 15px; line-height: 1.6;">
                If you didn't request this verification code, you can safely ignore this email. Your account security is
                important to us.
              </p>

              <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">

              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding: 20px; background-color: #f7fafc; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; color: #2d3748; font-size: 14px; font-weight: 600;">
                      🔒 Security Tips
                    </p>
                    <ul style="margin: 0; padding-left: 20px; color: #4a5568; font-size: 13px; line-height: 1.8;">
                      <li>Never share this code with anyone</li>
                      <li>We will never ask for your code via phone or email</li>
                      <li>If you suspect suspicious activity, contact our support team immediately</li>
                    </ul>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td
              style="background-color: #f7fafc; padding: 30px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
              <p style="margin: 0 0 10px 0; color: #718096; font-size: 14px; line-height: 1.6;">
                Need help? Contact our support team.
              </p>
              <p style="margin: 0; color: #a0aec0; font-size: 12px;">
                © {{ date('Y') }} PT SINTESA TALENTA ASIA. All rights reserved.
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>
</body>

</html>
