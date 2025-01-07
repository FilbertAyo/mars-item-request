<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f2f2f2;
            color: #333;
        }
        .container {
            text-align: center;
        }
        .container h1 {
            font-size: 8rem;
            margin: 0;
            color: #153357;
        }
        .container h2 {
            font-size: 2rem;
            margin: 10px 0;
        }
        .container p {
            font-size: 1.2rem;
            margin: 20px 0;
        }
        .container a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #153357;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .container a:hover {
            background-color: #153357;
        }
        .image {
            width: 300px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image">
            <img src="{{ asset('image/exp.jpg') }}" alt="404 Error" style="width: 100%; height: auto;">
        </div>
        <h2>Oops! Page Expired</h2>
        <p>It seems like the page you're looking is expired.</p>
        <a href="{{ url('/') }}">Go Back Home</a>
    </div>
</body>
</html>
