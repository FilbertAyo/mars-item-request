<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mars Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #153357;
            font-size: 24px;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .content ul {
            padding-left: 20px;
        }

        .content li {
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background-color: #153357;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }

        .footer a {
            color: #153357;
            text-decoration: none;
        }
    </style>
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
            <a href="http://192.168.1.172:8000/" class="btn">Go to Dashboard</a>

            <p>Please keep this information secure and do not share it with anyone.</p>
        </div>

        <div class="footer">
            <p>If you have any issues or need help, feel free to <a href="marscommunication.team@gmail.com">contact us</a>.</p>
            <p>Thank you for joining Mars! 🚀</p>
        </div>
    </div>
</body>
</html>
