
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Kos Bu Peni')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #176b4d;
            --primary-dark: #10543c;
            --primary-light: #e9f5ef;
            --background: #f6f8f6;
            --surface: #ffffff;
            --text-main: #1d3028;
            --text-muted: #78877f;
            --border: #e8eee9;
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text-main);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        /* NAVBAR */
        .main-navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--text-main) !important;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.6px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 12px;
            font-size: 21px;
        }

        .brand-caption {
            display: block;
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 500;
            letter-spacing: 0.4px;
            margin-top: -3px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* BUTTONS */
        .btn-green {
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 9px 17px;
            font-size: 13px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-green:hover {
            background: var(--primary-dark);
            color: white;
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline-green {
            background: white;
            color: var(--primary);
            border: 1px solid #cbded3;
            border-radius: 10px;
            padding: 9px 17px;
            font-size: 13px;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-outline-green:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .btn-light-custom {
            background: #f1f5f2;
            color: var(--text-main);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 9px 17px;
            font-size: 13px;
            font-weight: 700;
        }

        .btn-light-custom:hover {
            background: #e7eee9;
            color: var(--text-main);
        }

        /* MAIN CONTENT */
        .main-container {
            max-width: 1240px;
            margin: auto;
            padding: 38px 24px 60px;
            min-height: calc(100vh - 150px);
        }

        /* GLOBAL COMPONENTS */
        .card-custom {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 5px 20px rgba(24, 58, 39, 0.035);
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: var(--text-main);
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 11px 13px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(23, 107, 77, 0.10);
        }

        .table {
            --bs-table-bg: transparent;
            vertical-align: middle;
        }

        .table thead th {
            background: #f7faf8;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 14px;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 14px;
            font-size: 13px;
            border-bottom: 1px solid #edf1ee;
        }

        .alert {
            border: none;
            border-radius: 12px;
            font-size: 14px;
        }

        /* FOOTER */
        .main-footer {
            border-top: 1px solid var(--border);
            background: white;
            padding: 20px 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .main-navbar {
                padding: 12px 0;
            }

            .navbar-brand {
                font-size: 17px;
            }

            .brand-icon {
                width: 36px;
                height: 36px;
            }

            .navbar-actions {
                gap: 6px;
            }

            .navbar-actions .btn {
                padding: 7px 10px;
                font-size: 11px;
            }

            .main-container {
                padding: 25px 15px 40px;
            }

            .page-title {
                font-size: 22px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<nav class="main-navbar">
    <div class="container d-flex align-items-center justify-content-between">

        <a class="navbar-brand text-decoration-none" href="{{ route('home') }}">
            <span class="brand-icon">🏡</span>

            <span>
                Kos Bu Peni
                <span class="brand-caption">TEMPAT TINGGAL NYAMAN</span>
            </span>
        </a>

        <div class="navbar-actions">

            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light-custom">
                    Dashboard
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-green">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-green">
                    Login
                </a>

                <a href="{{ route('register') }}" class="btn btn-green">
                    Daftar
                </a>
            @endauth

        </div>
    </div>
</nav>

<main class="main-container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

</main>

<footer class="main-footer">
    &copy; {{ date('Y') }} <strong>Kos Bu Peni</strong>.
    Sistem Informasi Penyewaan Kos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
