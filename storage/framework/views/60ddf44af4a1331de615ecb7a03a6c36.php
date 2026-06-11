<?php $__env->startSection('content'); ?>
<h3 class="mb-4">Tambah Layanan</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?php echo e(route('services.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Nama Layanan</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary">
                    Kembali
                </a>
                <button class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SEMESTER 4\PENGUJIAN\-Kasir-Barbershop\resources\views/services/create.blade.php ENDPATH**/ ?>