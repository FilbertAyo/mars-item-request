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
            <h1>Request Rejected</h1>
        </div>

        <div class="content">
            <p>Hello {{ $name }}, </p>
            <p>We regret to inform you that your petty cash request for "{{ $reason }}" has been rejected.</p>

            <p>To review the request details and understand the reason for rejection, please click the button below:</p>
            <a href="https://pettycash.marscommltd.com/petty/{{ $id }}" class="btn">View Details</a>
        </div>

        <div class="footer">
            <p>If you have any issues or need clarification, feel free to <a href="mailto:marscommunication.team@gmail.com">contact us</a>.</p>
            <p>Thank you for your understanding and continued efforts! 🚀</p>
        </div>
    </div>

</body>
</html>
