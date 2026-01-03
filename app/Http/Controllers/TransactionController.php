<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $transactions = Transaction::with('service')->latest()->get();

        $totalHariIni = Transaction::whereDate('created_at', now()->toDateString())
            ->sum('total');

        $totalSemua = Transaction::sum('total');

        return view('transactions.index', compact(
            'services',
            'transactions',
            'totalHariIni',
            'totalSemua'
        ));
    }

    public function store(Request $request)
    {
    $request->validate([
        'service_id' => 'required|exists:services,id',
        'qty' => 'required|integer|min:1',
    ]);

    $service = Service::findOrFail($request->service_id);
    $total = $service->price * $request->qty;

    Transaction::create([
        'service_id' => $service->id,
        'qty' => $request->qty,
        'total' => $total,
    ]);

    return redirect()->back()->with('success', 'Transaksi berhasil disimpan');
    }
}