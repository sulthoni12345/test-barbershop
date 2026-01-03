@extends('layouts.app')

@section('content')
<h3 class="mb-4">Tambah Layanan</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Layanan</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('services.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
                <button class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
