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
        $list = DaftarPoli::with(['pasien', 'jadwalPeriksa'])
            ->whereHas('jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->whereDoesntHave('periksas')
            ->orderBy('created_at')
            ->get();

        return view('dokter.periksa-pasien.index', compact('list'));
    }

    public function edit($id)
    {
        $daftarPoli = DaftarPoli::with(['pasien', 'jadwalPeriksa'])
            ->whereHas('jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->findOrFail($id);

        $obats = Obat::orderBy('nama_obat')->get();

        return view('dokter.periksa-pasien.edit', compact('daftarPoli', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'obat_ids'   => 'required|array|min:1',
            'obat_ids.*' => 'exists:obat,id',
            'catatan'    => 'nullable|string',
        ]);

        $daftarPoli = DaftarPoli::whereHas('jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $obats = Obat::whereIn('id', $request->obat_ids)->get();
            $totalObat = $obats->sum('harga');
            $biayaJasa = 150000; // sesuaikan kebutuhan
            $totalBiaya = $biayaJasa + $totalObat;

            $periksa = Periksa::create([
                'id_daftar_poli' => $daftarPoli->id,
                'tgl_periksa'    => now(),
                'catatan'        => $request->catatan,
                'biaya_periksa'  => $totalBiaya,
            ]);

            foreach ($obats as $obat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat'    => $obat->id,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('dokter.periksa-pasien.index')
                ->with('message', 'Data periksa berhasil disimpan.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('message', 'Gagal menyimpan data periksa.')
                ->with('type', 'error');
        }
    }
}