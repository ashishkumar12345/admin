<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sembark URL Shortener</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- TOP NAVBAR WITH LOGOUT BUTTON -->
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Sembark Shortener</a>
            
            <div class="d-flex align-items-center gap-3">
                <span class="text-light">
                    <strong>{{ Auth::user()->name }}</strong> 
                    <span class="badge bg-primary ms-1">{{ Auth::user()->role }}</span>
                </span>

                <!-- LOGOUT FORM BUTTON -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <!-- MAIN DASHBOARD CONTENT -->
    <div class="container pb-5">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>