@extends('layouts.app')

@section('content')
<h3 class="mb-3">Data Layanan</h3>

<a href="{{ route('services.create') }}" class="btn btn-primary mb-3">
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
        @forelse ($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus layanan?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">
                    Belum ada layanan
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
