<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome | IT UNIWIDE 2025</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-family: 'Instrument Sans', sans-serif;
        }

        .welcome-container {
            max-width: 600px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .btn-custom {
            background-color: #ffc107;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>

    <div class="welcome-container">
        <h1 class="fw-bold">Welcome to IT UNIWIDE 2025</h1>
        <p class="lead">An exciting event where IT students from all campuses showcase their skills, projects, and
            innovations.</p>

        <i class="fa-solid fa-laptop-code fa-3x my-3"></i>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn btn-custom">Join the Event</a>
            <a href="#" class="btn btn-light">Learn More</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
