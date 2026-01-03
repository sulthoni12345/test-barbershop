<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Barbershop Kasir</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-image: url('/images/login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .overlay {
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
        }

        .glass {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(14px);
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,0.25);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center text-white relative">

<div class="overlay absolute inset-0"></div>

<div class="relative z-10 w-full max-w-md p-8 glass shadow-2xl">

    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold tracking-wide">Barbershop Kasir</h1>
        <p class="text-sm text-gray-200 mt-2">
            Sistem manajemen kasir modern
        </p>
    </div>

    {{-- Status --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-green-300">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" required autofocus
                   class="w-full px-4 py-2 rounded bg-white/20 text-white placeholder-gray-200
                          focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2 rounded bg-white/20 text-white placeholder-gray-200
                          focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="underline hover:text-blue-300">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-2 mt-4 rounded bg-blue-600 hover:bg-blue-700 transition font-semibold">
            Login
        </button>
    </form>

</div>

</body>
</html>
