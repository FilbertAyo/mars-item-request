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
            <h1>Petty Cash Request Approved for {{ $name }}</h1>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>The petty cash request for <strong>{{ $reason }}</strong> has been fully approved and is now ready for disbursement.</p>

            <p>Please proceed with providing the funds to {{ $name }}. For your reference, click the button below to view the request details:</p>
            <a href="https://marscommltd.com/final_approval/{{ $id }}" class="btn">View Request</a>
        </div>

        <div class="footer">
            <p>If you have any issues or need help, feel free to <a href="mailto:marscommunication.team@gmail.com">contact us</a>.</p>
            <p>Thank you for being part of Mars! 🚀</p>
        </div>
    </div>

</body>
</html>
