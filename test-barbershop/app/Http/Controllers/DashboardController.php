<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalHariIni = Transaction::whereDate('created_at', now())->sum('total');
        $totalSemua = Transaction::sum('total');
        $jumlahTransaksi = Transaction::count();

        return view('dashboard', compact(
            'totalHariIni',
            'totalSemua',
            'jumlahTransaksi'
        ));
    }
}