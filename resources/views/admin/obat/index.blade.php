<x-layouts.app title="Data Obat">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Data Obat
        </h2>

        <a href="{{ route('obat.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 
                  bg-primary hover:bg-primary/90 
                  text-white text-sm font-semibold 
                  rounded-xl transition">
            <i class="fas fa-plus text-xs"></i>
            Tambah Obat
        </a>
    </div>

    {{-- Stok Warning Banner --}}
    @php
        $stokMenipisCount = $obats->filter(fn($o) => $o->stokMenipis())->count();
        $stokHabisCount   = $obats->filter(fn($o) => $o->stokHabis())->count();
    @endphp

    @if($stokHabisCount > 0)
        <div class="alert alert-error mb-4 rounded-xl shadow-sm">
            <i class="fas fa-circle-xmark"></i>
            <span><strong>{{ $stokHabisCount }} obat</strong> stoknya sudah <strong>habis</strong>! Segera lakukan pengisian stok.</span>
        </div>
    @endif

    @if($stokMenipisCount > 0)
        <div class="alert alert-warning mb-4 rounded-xl shadow-sm">
            <i class="fas fa-triangle-exclamation"></i>
            <span><strong>{{ $stokMenipisCount }} obat</strong> stoknya <strong>menipis</strong> (kurang dari 10 unit).</span>
        </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Obat</th>
                            <th class="px-6 py-4">Kemasan</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse($obats as $obat)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $obat->nama_obat }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold 
                                             rounded-full bg-green-100 text-green-600">
                                        {{ $obat->kemasan ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                </td>

                                {{-- Stok dengan indikator visual --}}
                                <td class="px-6 py-4 text-center">
                                    @if($obat->stokHabis())
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-600">
                                            <i class="fas fa-circle-xmark text-xs"></i>
                                            Habis
                                        </span>
                                    @elseif($obat->stokMenipis())
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-600">
                                            <i class="fas fa-triangle-exclamation text-xs"></i>
                                            {{ $obat->stok }} unit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-600">
                                            <i class="fas fa-check text-xs"></i>
                                            {{ $obat->stok }} unit
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 flex-wrap">

                                        {{-- Edit --}}
                                        <a href="{{ route('obat.edit', $obat->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-2 
                                              bg-amber-500 hover:bg-amber-600 
                                              text-white text-xs font-semibold 
                                              rounded-lg transition">
                                            <i class="fas fa-pen text-xs"></i>
                                            Edit
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('obat.destroy', $obat->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus obat ini?')"
                                                class="inline-flex items-center gap-1 px-3 py-2 
                                                   bg-red-500 hover:bg-red-600 
                                                   text-white text-xs font-semibold 
                                                   rounded-lg transition">
                                                <i class="fas fa-trash text-xs"></i>
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-3xl mb-3"></i>
                                        Belum ada data obat
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>



</x-layouts.app>
