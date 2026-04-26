<x-layouts.app title="Edit Periksa Pasien">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Periksa Pasien</h1>

        <div class="bg-white rounded-xl shadow p-6">
            <form action="{{ route('dokter.periksa-pasien.update', $daftarPoli->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Pasien</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100"
                        value="{{ $daftarPoli->pasien->nama ?? '-' }}" readonly>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Keluhan</label>
                    <textarea class="w-full border rounded px-3 py-2 bg-gray-100" rows="3" readonly>{{ $daftarPoli->keluhan }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Pilih Obat</label>
                    <div class="space-y-2">
                        @foreach ($obats as $obat)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="obat_ids[]" value="{{ $obat->id }}">
                                <span>{{ $obat->nama_obat }} - Rp {{ number_format($obat->harga, 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('obat_ids')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Catatan</label>
                    <textarea name="catatan" rows="4" class="w-full border rounded px-3 py-2">{{ old('catatan') }}</textarea>
                </div>

                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </form>
        </div>
    </div>
