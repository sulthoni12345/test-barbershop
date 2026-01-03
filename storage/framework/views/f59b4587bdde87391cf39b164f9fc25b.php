<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Barbershop Kasir</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        body {
            background-image: url('/images/register.jpg');
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

    
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold tracking-wide">Daftar Akun</h1>
        <p class="text-sm text-gray-200 mt-2">
            Sistem Barbershop Kasir
        </p>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="mb-4 text-sm text-red-300">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div>
            <label class="block text-sm mb-1">Nama</label>
            <input type="text" name="name" required autofocus
                   class="w-full px-4 py-2 rounded bg-white/20 text-white
                          focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full px-4 py-2 rounded bg-white/20 text-white
                          focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2 rounded bg-white/20 text-white
                          focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-2 rounded bg-white/20 text-white
                          focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full py-2 mt-4 rounded bg-blue-600 hover:bg-blue-700 transition font-semibold">
            Daftar
        </button>
    </form>

    <div class="text-center mt-6 text-sm">
        Sudah punya akun?
        <a href="<?php echo e(route('login')); ?>" class="underline hover:text-blue-300">
            Login
        </a>
    </div>

</div>

</body>
</html>
<?php /**PATH C:\tubes_barbershop\tubes_barbershop\barbershop\resources\views/auth/register.blade.php ENDPATH**/ ?>