<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_json'     => 'required',
            'catatan'       => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = json_decode($request->obat_json, true);

        // ── Validasi stok setiap obat yang dipilih ──────────────────────────
        $stokHabisErrors = [];
        foreach ($obatIds as $idObat) {
            $obat = Obat::find($idObat);
            if (!$obat || $obat->stok <= 0) {
                $stokHabisErrors[] = $obat ? $obat->nama_obat : "ID #{$idObat}";
            }
        }

        if (!empty($stokHabisErrors)) {
            $namaObat = implode(', ', $stokHabisErrors);
            return back()
                ->withInput()
                ->with('error', "Stok obat berikut sudah habis: {$namaObat}. Silakan pilih obat lain atau hubungi admin.");
        }
        // ────────────────────────────────────────────────────────────────────

        DB::transaction(function () use ($request, $obatIds) {
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa'    => now(),
                'catatan'        => $request->catatan,
                'biaya_periksa'  => $request->biaya_periksa + 150000,
            ]);

            foreach ($obatIds as $idObat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat'    => $idObat,
                ]);

                // Kurangi stok otomatis setiap obat yang diresepkan
                Obat::where('id', $idObat)->decrement('stok');
            }
        });

        return redirect()->route('periksa-pasien.index')
            ->with('success', 'Data periksa berhasil disimpan dan stok obat telah diperbarui.');
    }
}