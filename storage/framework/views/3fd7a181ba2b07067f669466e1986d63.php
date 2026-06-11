<?php $__env->startSection('content'); ?>
<h3 class="mb-3">Data Layanan</h3>

<a href="<?php echo e(route('services.create')); ?>" class="btn btn-primary mb-3">
    + Tambah Layanan
</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nama Layanan</th>
            <th>Harga</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($service->name); ?></td>
                <td>Rp <?php echo e(number_format($service->price, 0, ',', '.')); ?></td>
                <td>
                    <a href="<?php echo e(route('services.edit', $service)); ?>" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="<?php echo e(route('services.destroy', $service)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus layanan?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="text-center text-muted">
                    Belum ada layanan
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SEMESTER 4\PENGUJIAN\-Kasir-Barbershop\resources\views/services/index.blade.php ENDPATH**/ ?>