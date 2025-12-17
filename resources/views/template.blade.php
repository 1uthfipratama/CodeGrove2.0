<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">

    <!-- Icons & Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-b7fVtM9cyFOsBQ33jjcgzvEihQ/HUkNzxaz2YsmiVjCwXTlzAIXazhbugzuDUFcAU9M7IO6N25DzDf7cXr3XxQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/codegrove-custom.css') }}">
</head>
<body class="cg-app">
    <div id="globalSkeleton" class="cg-skeleton-overlay">
        <div class="cg-skeleton-grid">
            @for ($i = 0; $i < 4; $i++)
                <div class="cg-skeleton-card">
                    <div class="cg-skeleton-line w-25"></div>
                    <div class="cg-skeleton-line w-75"></div>
                    <div class="cg-skeleton-line w-50"></div>
                </div>
            @endfor
        </div>
    </div>

    <div id="toastContainer" class="cg-toast-container" aria-live="polite" aria-atomic="true"></div>

    @yield('content')

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/codegrove-animations.js') }}"></script>
</body>
</html>
