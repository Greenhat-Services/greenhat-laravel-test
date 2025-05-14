<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Greenhat!</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome!</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $employee->username }}!</h2>
            <p>Thank you for registering with us. This is a confirmation email to let you know that you have successfully registered with us.</p>
            <p>If you have any questions, feel free to reach out to our support team at support@greenhat.net.au.</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p>Greenhat Team</p>
        </div>
    </div>
</body>
</html>
