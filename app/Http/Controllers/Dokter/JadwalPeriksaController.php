<?php

namespace App\Http\Controllers\Dokter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())
            ->orderByRaw("
                FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')
            ")
            ->orderBy('jam_mulai')
            ->get();

        return view('dokter.jadwal-periksa.index', compact('jadwals'));
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil ditambahkan.')
            ->with('type', 'success');
    }

    public function edit($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        if ($jadwal->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('dokter.jadwal-periksa.edit')->with([
            'jadwal' => $jadwal
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $jadwal = JadwalPeriksa::findOrFail($id);

        if ($jadwal->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $jadwal->update($validated);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil diupdate.')
            ->with('type', 'success');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        if ($jadwal->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $jadwal->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil dihapus.')
            ->with('type', 'success');
    }
}
