<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mars Communications</title>
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Petty Cash Request from {{ $name }}</h1>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>A new petty cash request for <strong>{{ $reason }}</strong> has successfully passed the first approval and is now awaiting your review.</p>

            <p>To review the details and provide your approval, please click the button below:</p>
            <a href="https://pettycash.marscommltd.com/first_approval/{{ $id }}" class="btn">View Request</a>
        </div>

        <div class="footer">
            <p>If you have any issues or need help, feel free to <a href="mailto:marscommunication.team@gmail.com">contact us</a>.</p>
            <p>Thank you for being part of Mars! 🚀</p>
        </div>
    </div>


</body>
</html>
