@extends('layouts.app')

@section('content')
<h3 class="mb-4">Transaksi Kasir</h3>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Hari Ini</h6>
                <h4>Rp {{ number_format($totalHariIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Keseluruhan</h6>
                <h4>Rp {{ number_format($totalSemua, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Jumlah Transaksi</h6>
                <h4>{{ $transactions->count() }}</h4>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('transactions.store') }}" method="POST" class="card p-3 mb-4">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <label>Layanan</label>
            <select name="service_id" class="form-select" required>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}">
                        {{ $service->name }} - Rp {{ number_format($service->price,0,',','.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Qty</label>
            <input type="number" name="qty" class="form-control" min="1" value="1" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-success w-100">Simpan</button>
        </div>
    </div>
</form>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Layanan</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $trx)
            <tr>
                <td>{{ $trx->created_at->format('d/m/Y') }}</td>
                <td>{{ $trx->service->name }}</td>
                <td>{{ $trx->qty }}</td>
                <td>Rp {{ number_format($trx->total,0,',','.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
