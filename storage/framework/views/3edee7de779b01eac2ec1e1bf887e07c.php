<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Barbershop Kasir System</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        body {
            background-image: url('/images/barber-hero.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .overlay {
            background: rgba(0,0,0,0.65);
        }
        .glass {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(14px);
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,0.25);
        }
    </style>
</head>

<body class="text-white relative">


<section class="relative min-h-screen flex items-center justify-center text-center px-6">
    <div class="overlay absolute inset-0"></div>

    <div class="relative z-10 max-w-3xl">
        <h1 class="text-5xl font-extrabold leading-tight mb-6">
            Barbershop Kasir System
        </h1>

        <p class="text-lg text-gray-200 mb-8">
            Sistem kasir barbershop berbasis Laravel untuk mengelola layanan,
            transaksi, dan laporan pemasukan secara sederhana dan efisien.
        </p>

        <div class="flex justify-center gap-4">
            <a href="<?php echo e(route('login')); ?>"
               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded text-white font-semibold transition">
                Login Sistem
            </a>

            <a href="#fitur"
               class="px-6 py-3 bg-white/20 hover:bg-white/30 rounded text-white transition">
                Lihat Fitur
            </a>
        </div>
    </div>
</section>


<section id="fitur" class="py-24 bg-black/70">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-12">Fitur Utama Sistem</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="glass p-6">
                <h3 class="text-xl font-semibold mb-3">Manajemen Layanan</h3>
                <p class="text-sm text-gray-200">
                    Mengelola daftar layanan potong rambut beserta harga
                    secara fleksibel dan terstruktur.
                </p>
            </div>

            <div class="glass p-6">
                <h3 class="text-xl font-semibold mb-3">Transaksi Kasir</h3>
                <p class="text-sm text-gray-200">
                    Pencatatan transaksi pelanggan dengan perhitungan
                    total otomatis untuk mempercepat proses kasir.
                </p>
            </div>

            <div class="glass p-6">
                <h3 class="text-xl font-semibold mb-3">Dashboard Ringkas</h3>
                <p class="text-sm text-gray-200">
                    Menampilkan ringkasan pemasukan harian dan total
                    transaksi barbershop secara real-time.
                </p>
            </div>
        </div>
    </div>
</section>


<section class="py-24 bg-black/80 text-center">
    <h2 class="text-3xl font-bold mb-6">Hak Akses Pengguna</h2>
    <p class="text-gray-300 mb-12">
        Sistem mendukung pengelolaan berdasarkan peran pengguna
    </p>

    <div class="flex justify-center gap-16 flex-wrap">
        <div>
            <h3 class="font-semibold text-lg mb-2">Admin</h3>
            <p class="text-sm text-gray-300">
                Mengelola layanan dan memantau laporan transaksi
            </p>
        </div>

        <div>
            <h3 class="font-semibold text-lg mb-2">Kasir</h3>
            <p class="text-sm text-gray-300">
                Melakukan input transaksi pelanggan
            </p>
        </div>
    </div>
</section>


<footer class="py-6 text-center text-sm text-gray-400 bg-black">
    © 2025 Barbershop Kasir — Laravel Project
</footer>

</body>
</html>
<?php /**PATH C:\tubes_barbershop\tubes_barbershop\barbershop\resources\views/landing.blade.php ENDPATH**/ ?>