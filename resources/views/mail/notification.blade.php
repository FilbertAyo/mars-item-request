<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mars Registration</title>
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Mars, {{ $name }}!</h1>
        </div>

        <div class="content">
            <p>Congratulations! Your registration is complete. Here are your login details:</p>

            <ul>
                <li><strong>Email:</strong> {{ $email }}</li>
                <li><strong>Password:</strong> {{ $password }}</li>
            </ul>

            <p>You can now log in to your account using the link below:</p>
            <a href="https://pettycash.marscommltd.com" class="btn">Go to Dashboard</a>

            <p>Please keep this information secure and do not share it with anyone.</p>
        </div>

        <div class="footer">
            <p>If you have any issues or need help, feel free to <a href="marscommunication.team@gmail.com">contact us</a>.</p>
            <p>Thank you for joining Mars! 🚀</p>
        </div>
    </div>
</body>
</html>
