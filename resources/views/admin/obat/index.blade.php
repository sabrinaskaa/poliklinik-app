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

                                        {{-- Tombol Tambah Stok --}}
                                        <button type="button"
                                            onclick="openModalStok({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}', 'tambah')"
                                            class="inline-flex items-center gap-1 px-3 py-2 
                                              bg-emerald-500 hover:bg-emerald-600 
                                              text-white text-xs font-semibold 
                                              rounded-lg transition">
                                            <i class="fas fa-plus text-xs"></i>
                                            Stok
                                        </button>

                                        {{-- Tombol Kurangi Stok --}}
                                        <button type="button"
                                            onclick="openModalStok({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}', 'kurangi')"
                                            class="inline-flex items-center gap-1 px-3 py-2 
                                              bg-orange-500 hover:bg-orange-600 
                                              text-white text-xs font-semibold 
                                              rounded-lg transition">
                                            <i class="fas fa-minus text-xs"></i>
                                            Stok
                                        </button>

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

    {{-- ===== MODAL STOK ===== --}}
    <div id="modalStok"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">

            {{-- Modal Header --}}
            <div id="modalHeader" class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="modalIconWrap" class="w-10 h-10 rounded-xl flex items-center justify-center">
                        <i id="modalIcon" class="fas text-lg"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="font-bold text-slate-800 text-base"></h3>
                        <p id="modalSubtitle" class="text-xs text-slate-400 mt-0.5"></p>
                    </div>
                </div>
                <button onclick="closeModalStok()"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
                    <i class="fas fa-xmark text-slate-500 text-sm"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <form id="formStok" method="POST">
                @csrf
                <div class="px-6 pb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center border-2 rounded-xl px-4 py-2 focus-within:border-primary transition">
                        <i id="modalInputIcon" class="fas fa-boxes-stacked text-slate-400 mr-3"></i>
                        <input type="number" name="jumlah" id="jumlahInput"
                            class="w-full focus:outline-none text-slate-700 font-semibold"
                            min="1" placeholder="Masukkan jumlah..." required>
                        <span class="text-slate-400 text-sm ml-2">unit</span>
                    </div>

                    <div class="flex gap-3 mt-5">
                        <button type="submit" id="modalSubmitBtn"
                            class="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition">
                            Konfirmasi
                        </button>
                        <button type="button" onclick="closeModalStok()"
                            class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition">
                            Batal
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        const tambahStokBase = "{{ url('admin/obat') }}";

        function openModalStok(id, nama, tipe) {
            const modal     = document.getElementById('modalStok');
            const form      = document.getElementById('formStok');
            const title     = document.getElementById('modalTitle');
            const subtitle  = document.getElementById('modalSubtitle');
            const header    = document.getElementById('modalHeader');
            const iconWrap  = document.getElementById('modalIconWrap');
            const icon      = document.getElementById('modalIcon');
            const submitBtn = document.getElementById('modalSubmitBtn');

            document.getElementById('jumlahInput').value = '';

            if (tipe === 'tambah') {
                form.action = `${tambahStokBase}/${id}/tambah-stok`;
                title.textContent = 'Tambah Stok Obat';
                subtitle.textContent = nama;
                header.className = 'px-6 py-4 flex items-center justify-between bg-emerald-50 border-b border-emerald-100';
                iconWrap.className = 'w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-100';
                icon.className = 'fas fa-plus text-emerald-600 text-lg';
                submitBtn.className = 'flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition bg-emerald-500 hover:bg-emerald-600';
            } else {
                form.action = `${tambahStokBase}/${id}/kurangi-stok`;
                title.textContent = 'Kurangi Stok Obat';
                subtitle.textContent = nama;
                header.className = 'px-6 py-4 flex items-center justify-between bg-orange-50 border-b border-orange-100';
                iconWrap.className = 'w-10 h-10 rounded-xl flex items-center justify-center bg-orange-100';
                icon.className = 'fas fa-minus text-orange-600 text-lg';
                submitBtn.className = 'flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition bg-orange-500 hover:bg-orange-600';
            }

            modal.classList.remove('hidden');
        }

        function closeModalStok() {
            document.getElementById('modalStok').classList.add('hidden');
        }

        // Tutup modal jika klik di luar
        document.getElementById('modalStok').addEventListener('click', function(e) {
            if (e.target === this) closeModalStok();
        });
    </script>
    @endpush

</x-layouts.app>
