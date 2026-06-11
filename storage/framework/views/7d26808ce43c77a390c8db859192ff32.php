<?php $__env->startSection('content'); ?>
<h3 class="mb-4">Transaksi Kasir</h3>


<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6>Total Hari Ini</h6>
                <h4>Rp <?php echo e(number_format($totalHariIni, 0, ',', '.')); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6>Total Keseluruhan</h6>
                <h4>Rp <?php echo e(number_format($totalSemua, 0, ',', '.')); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-dark text-white">
            <div class="card-body">
                <h6>Jumlah Transaksi</h6>
                <h4><?php echo e($transactions->count()); ?></h4>
            </div>
        </div>
    </div>
</div>


<div class="card shadow-sm mb-4">
    <div class="card-header fw-bold">+ Tambah Transaksi</div>
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <form action="<?php echo e(route('transactions.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Layanan</label>
                    <select name="service_id" class="form-select" required>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>">
                                <?php echo e($service->name); ?> - Rp <?php echo e(number_format($service->price, 0, ',', '.')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number" name="qty" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-success w-100">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card shadow-sm mb-3">
    <div class="card-header fw-bold">🔍 Filter Riwayat Transaksi</div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('transactions.index')); ?>">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="<?php echo e(request('dari')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="<?php echo e(request('sampai')); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="<?php echo e(route('transactions.index')); ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header fw-bold">Riwayat Transaksi</div>
    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Layanan</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($i + 1); ?></td>
                        <td><?php echo e($trx->created_at->format('d/m/Y H:i')); ?></td>
                        <td><?php echo e($trx->service->name); ?></td>
                        <td><?php echo e($trx->qty); ?></td>
                        <td>Rp <?php echo e(number_format($trx->total, 0, ',', '.')); ?></td>
                        <td>
                            <a href="<?php echo e(route('transactions.struk', $trx->id)); ?>"
                               class="btn btn-sm btn-outline-primary" target="_blank">
                                🖨️ Cetak Struk
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Tidak ada data transaksi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SEMESTER 4\PENGUJIAN\-Kasir-Barbershop\resources\views/transactions/index.blade.php ENDPATH**/ ?>