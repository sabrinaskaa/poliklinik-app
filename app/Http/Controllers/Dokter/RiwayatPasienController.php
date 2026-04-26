<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        $riwayats = Periksa::with([
                'daftarPoli.pasien',
                'daftarPoli.jadwalPeriksa.dokter',
                'detailPeriksas.obat'
            ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->latest('tgl_periksa')
            ->get();

        return view('dokter.riwayat-pasien.index', compact('riwayats'));
    }

    public function show($id)
    {
        $riwayat = Periksa::with([
                'daftarPoli.pasien',
                'daftarPoli.jadwalPeriksa.dokter.poli',
                'detailPeriksas.obat'
            ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->findOrFail($id);

        return view('dokter.riwayat-pasien.show', compact('riwayat'));
    }
}