<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mars Communications</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all_dash.css') }}">
</head>

<body class="font-sans bg-light text-dark d-flex flex-column min-vh-100">

    <!-- App Bar -->
    <header class="bg-dark py-3 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo on the left -->
          <img src="/image/longlogo.png" alt="" class="img-fluid" style="max-height: 50px;">


            <!-- Login Button on the right -->
            <div>
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-light">Log in</a>
                @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1">
        <div class="container d-flex align-items-center justify-content-between" style="min-height: 70vh;">
            <!-- Text content on the left -->
            <div class="text-left">
                <h1 class="display-4"> Mars Communications management system</h1>
                <p class="lead mb-3">Managing all your communication needs with ease clicks.</p>
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-dark">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-dark">Log in to start</a>
                @endauth
                @endif
            </div>

            <!-- Image on the right -->
            <div>
                <img src="/image/img5.png" alt="Mars Logo" class="img-fluid" style="max-width: 600px;">
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-center py-3 mt-auto">
        <p class="mb-0 text-light">&copy; 2024 Mars Communications.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
