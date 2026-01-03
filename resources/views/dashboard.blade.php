@extends('layouts.app')

@section('content')
<h4 class="mb-4">Dashboard Kasir</h4>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Hari Ini</h6>
                <h4 class="fw-bold">Rp {{ number_format($totalHariIni ?? 0) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Keseluruhan</h6>
                <h4 class="fw-bold">Rp {{ number_format($totalSemua ?? 0) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Jumlah Transaksi</h6>
                <h4 class="fw-bold">{{ $jumlahTransaksi ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection
