<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Barbershop Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            Barbershop Kasir
        </a>
        <div class="d-flex align-items-center gap-3">
            <ul class="navbar-nav flex-row gap-3 mb-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'fw-bold text-dark' : '' }}"
                       href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'fw-bold text-dark' : '' }}"
                       href="{{ route('transactions.index') }}">Transaksi</a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services.*') ? 'fw-bold text-dark' : '' }}"
                       href="{{ route('services.index') }}">Kelola Layanan</a>
                </li>
                @endif
            </ul>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    {{ auth()->user()->name }}
                    <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : 'secondary' }} ms-1">
                        {{ auth()->user()->role }}
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="container my-4">
    @yield('content')
</main>

<footer class="text-center text-muted py-3">
    © 2025 Barbershop Kasir — Laravel Project
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
