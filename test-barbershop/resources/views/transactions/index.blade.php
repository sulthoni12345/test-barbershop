@extends('layouts.app')

@section('content')
<h3 class="mb-4">Transaksi Kasir</h3>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6>Total Hari Ini</h6>
                <h4>Rp {{ number_format($totalHariIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6>Total Keseluruhan</h6>
                <h4>Rp {{ number_format($totalSemua, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-dark text-white">
            <div class="card-body">
                <h6>Jumlah Transaksi</h6>
                <h4>{{ $transactions->count() }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- Form Tambah Transaksi --}}
<div class="card shadow-sm mb-4">
    <div class="card-header fw-bold">+ Tambah Transaksi</div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Layanan</label>
                    <select name="service_id" class="form-select" required>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                            </option>
                        @endforeach
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

{{-- Filter Riwayat --}}
<div class="card shadow-sm mb-3">
    <div class="card-header fw-bold">🔍 Filter Riwayat Transaksi</div>
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Riwayat --}}
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
                @forelse ($transactions as $i => $trx)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $trx->service->name }}</td>
                        <td>{{ $trx->qty }}</td>
                        <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transactions.struk', $trx->id) }}"
                               class="btn btn-sm btn-outline-primary" target="_blank">
                                🖨️ Cetak Struk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
