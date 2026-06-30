<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $totalPoli    = Poli::count();
        $totalDokter  = User::where('role', 'dokter')->count();
        $totalPasien  = User::where('role', 'pasien')->count();
        $totalObat    = Obat::count();

        // Ambil 5 poli terbaru beserta jumlah dokternya
        $polis = Poli::withCount('dokters')->latest()->take(5)->get();

        // Obat dengan stok habis (stok = 0)
        $obatHabis = Obat::where('stok', '<=', 0)->orderBy('nama_obat')->get();

        // Obat dengan stok menipis (1–9)
        $obatMenipis = Obat::whereBetween('stok', [1, 9])->orderBy('stok')->get();

        return view('admin.dashboard', compact(
            'totalPoli',
            'totalDokter',
            'totalPasien',
            'totalObat',
            'polis',
            'obatHabis',
            'obatMenipis',
        ));
    }
}
