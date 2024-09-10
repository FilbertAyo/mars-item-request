<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mars communications | Purchase request</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all_dash.css') }}">
</head>

<body class="font-sans bg-light text-white d-flex flex-column min-vh-100">

    <!-- App Bar -->
    <header class="bg-dark py-3 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">MARS PURCHASE MANAGEGEMENT</h1>
            <div>
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-light">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-light">Log in</a>
                @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="d-flex justify-content-center align-items-center flex-grow-1">
        <img src="/image/marslogo.png" alt="FinTech Logo" class="img-fluid" style="max-width: 250px;">
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-center py-3 mt-auto">
        <p class="mb-0">&copy; 2024 mars communications.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
