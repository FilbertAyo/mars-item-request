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
            <h1>Payment Completed</h1>
        </div>

        <div class="content">
            <p>Hello {{ $name }}, </p>
            <p>Your petty cash request for "{{ $reason }}" has been approved, and the amount has been disbursed successfully.</p>

            <p>To review the request details for your records, please click the button below:</p>
            <a href="https://marscommltd.com/petty/{{ $id }}" class="btn">Review</a>
        </div>

        <div class="footer">
            <p>If you have any issues or need help, feel free to <a href="mailto:marscommunication.team@gmail.com">contact us</a>.</p>
            <p>Thank you for your dedication and efforts! 🚀</p>
        </div>
    </div>

</body>
</html>
