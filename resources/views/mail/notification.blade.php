@extends('mail.app')

@section('content')
    <div class="email-header">
        <h2>Welcome to Mars Portal, {{ $name }}!</h2>
    </div>
    <div>
        <p>Congratulations! Your registration is complete. Here are your login details:</p>

        <ul>
            <li><strong>Email:</strong> {{ $email }}</li>
            <li><strong>Password:</strong> {{ $password }}</li>
        </ul>
        <p>You can now log in to your account using the link below:</p>
        <p>
            <a href="{{ config('app.url') }}"
                style="display: inline-block; padding: 10px 20px; background-color: #1d72b8; color: white; text-decoration: none; border-radius: 4px;">
                Login
            </a>
        </p>
        <p>Please keep this information secure and do not share it with anyone.</p>
    </div>
@endsection
