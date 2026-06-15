<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran #{{ $transaction->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 30px;
        }

        .struk {
            background: white;
            width: 320px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .header { text-align: center; border-bottom: 2px dashed #333; padding-bottom: 12px; margin-bottom: 12px; }
        .header h2 { font-size: 18px; letter-spacing: 2px; }
        .header p { font-size: 11px; color: #555; margin-top: 2px; }

        .row-item { display: flex; justify-content: space-between; margin: 6px 0; font-size: 13px; }
        .label { color: #555; }

        .divider { border: none; border-top: 1px dashed #aaa; margin: 10px 0; }

        .total-box {
            background: #111;
            color: white;
            padding: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            font-weight: bold;
            margin: 10px 0;
        }

        .footer { text-align: center; font-size: 11px; color: #888; margin-top: 14px; padding-top: 10px; border-top: 1px dashed #ccc; }

        .btn-group { display: flex; gap: 10px; margin-top: 20px; }
        .btn {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }
        .btn-print { background: #0d6efd; color: white; }
        .btn-back  { background: #6c757d; color: white; }

        @media print {
            body { background: white; padding: 0; }
            .struk { box-shadow: none; border-radius: 0; }
            .btn-group { display: none; }
        }
    </style>
</head>
<body>

<div class="struk">

    {{-- Header --}}
    <div class="header">
        <h2>✂ BARBERSHOP</h2>
        <p>Struk Pembayaran</p>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Detail Transaksi --}}
    <div class="row-item">
        <span class="label">No. Transaksi</span>
        <span>#{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="row-item">
        <span class="label">Tanggal</span>
        <span>{{ $transaction->created_at->format('d/m/Y') }}</span>
    </div>
    <div class="row-item">
        <span class="label">Waktu</span>
        <span>{{ $transaction->created_at->format('H:i') }} WIB</span>
    </div>
    <div class="row-item">
        <span class="label">Kasir</span>
        <span>{{ auth()->user()->name }}</span>
    </div>

    <hr class="divider">

    {{-- Detail Layanan --}}
    <div class="row-item">
        <span class="label">Layanan</span>
        <span>{{ $transaction->service->name }}</span>
    </div>
    <div class="row-item">
        <span class="label">Harga Satuan</span>
        <span>Rp {{ number_format($transaction->service->price, 0, ',', '.') }}</span>
    </div>
    <div class="row-item">
    <span class="label">Orang</span>
    <span>{{ $transaction->qty }} orang</span>
</div>

    <hr class="divider">

    {{-- Total --}}
    <div class="total-box">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Terima kasih telah berkunjung!</p>
        <p>© 2025 Barbershop Kasir</p>
    </div>

    {{-- Tombol --}}
    <div class="btn-group">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print</button>
        <button class="btn btn-back" onclick="window.close()">✖ Tutup</button>
    </div>

</div>

</body>
</html>
