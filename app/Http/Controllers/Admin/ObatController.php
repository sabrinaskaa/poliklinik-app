<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan'   => 'required|string',
            'harga'     => 'required|integer',
            'stok'      => 'required|integer|min:0',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil dibuat')
            ->with('type', 'success');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit')->with([
            'obat' => $obat
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan'   => 'nullable|string',
            'harga'     => 'required|integer',
            'stok'      => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di edit')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di Hapus')
            ->with('type', 'success');
    }

    /**
     * Tambah stok obat secara manual oleh admin.
     */
    public function tambahStok(Request $request, string $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->increment('stok', $request->jumlah);

        return redirect()->route('obat.index')
            ->with('message', "Stok {$obat->nama_obat} berhasil ditambah sebanyak {$request->jumlah} unit.")
            ->with('type', 'success');
    }

    /**
     * Kurangi stok obat secara manual oleh admin.
     */
    public function kurangiStok(Request $request, string $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($id);

        if ($request->jumlah > $obat->stok) {
            return redirect()->route('obat.index')
                ->with('message', "Gagal: Stok {$obat->nama_obat} tidak mencukupi. Stok saat ini: {$obat->stok}.")
                ->with('type', 'error');
        }

        $obat->decrement('stok', $request->jumlah);

        return redirect()->route('obat.index')
            ->with('message', "Stok {$obat->nama_obat} berhasil dikurangi sebanyak {$request->jumlah} unit.")
            ->with('type', 'success');
    }
}