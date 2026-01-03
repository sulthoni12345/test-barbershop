<?php $__env->startSection('content'); ?>
<h4 class="mb-4">Dashboard Kasir</h4>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Hari Ini</h6>
                <h4 class="fw-bold">Rp <?php echo e(number_format($totalHariIni ?? 0)); ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Keseluruhan</h6>
                <h4 class="fw-bold">Rp <?php echo e(number_format($totalSemua ?? 0)); ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Jumlah Transaksi</h6>
                <h4 class="fw-bold"><?php echo e($jumlahTransaksi ?? 0); ?></h4>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\tubes_barbershop\tubes_barbershop\barbershop\resources\views/dashboard.blade.php ENDPATH**/ ?>