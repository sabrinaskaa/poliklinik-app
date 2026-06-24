<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        $dokterId = Auth::id();

        // Total jadwal milik dokter ini
        $totalJadwal = JadwalPeriksa::where('id_dokter', $dokterId)->count();

        // Pasien menunggu = pasien terdaftar di jadwal dokter ini yang belum diperiksa
        $pasienMenunggu = DaftarPoli::whereHas('jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->whereDoesntHave('periksas')
            ->count();

        // Total riwayat = periksa yang sudah selesai oleh dokter ini
        $totalRiwayat = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->count();

        // Jadwal periksa milik dokter ini (maks 5)
        $jadwals = JadwalPeriksa::where('id_dokter', $dokterId)
            ->orderBy('hari')
            ->take(5)
            ->get();

        return view('dokter.dashboard', compact(
            'totalJadwal',
            'pasienMenunggu',
            'totalRiwayat',
            'jadwals',
        ));
    }
}
