<x-layouts.app title="Admin Dashboard">

    {{-- ===== GREETING ===== --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Selamat Datang, {{ auth()->user()->nama ?? 'Admin' }} 👋
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            {{ now()->translatedFormat('l, d F Y') }} — Berikut ringkasan data sistem poliklinik.
        </p>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

        {{-- Total Poli --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-hospital text-blue-500 text-xl"></i>
                    </div>
                    <a href="{{ route('polis.index') }}"
                        class="text-xs font-semibold text-blue-500 hover:text-blue-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $totalPoli }}</div>
                <div class="text-sm text-slate-500">Total Poli</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-200"></div>
        </div>

        {{-- Total Dokter --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-user-doctor text-emerald-500 text-xl"></i>
                    </div>
                    <a href="{{ route('dokter.index') }}"
                        class="text-xs font-semibold text-emerald-500 hover:text-emerald-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $totalDokter }}</div>
                <div class="text-sm text-slate-500">Total Dokter</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-emerald-200"></div>
        </div>

        {{-- Total Pasien --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-bed-pulse text-amber-500 text-xl"></i>
                    </div>
                    <a href="{{ route('pasien.index') }}"
                        class="text-xs font-semibold text-amber-500 hover:text-amber-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $totalPasien }}</div>
                <div class="text-sm text-slate-500">Total Pasien</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-400 to-amber-200"></div>
        </div>

        {{-- Total Obat --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center">
                        <i class="fas fa-pills text-pink-500 text-xl"></i>
                    </div>
                    <a href="{{ route('obat.index') }}"
                        class="text-xs font-semibold text-pink-500 hover:text-pink-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $totalObat }}</div>
                <div class="text-sm text-slate-500">Total Obat</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-pink-400 to-pink-200"></div>
        </div>

    </div>

    {{-- ===== BOTTOM SECTION ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- Tabel Daftar Poli --}}
        <div class="xl:col-span-2 card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
            <div class="card-body p-0">

                {{-- Header Tabel --}}
                <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-base-200">
                    <h3 class="font-bold text-slate-700 text-base">Daftar Poli</h3>
                    <a href="{{ route('polis.index') }}"
                        class="text-sm text-blue-500 hover:text-blue-700 font-semibold flex items-center gap-1 transition">
                        Lihat Semua
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Nama Poli</th>
                                <th class="px-6 py-3">Keterangan</th>
                                <th class="px-6 py-3 text-center">Dokter</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($polis as $poli)
                                <tr class="hover:bg-slate-50 transition border-b border-base-100 last:border-0">
                                    <td class="px-6 py-4 font-semibold text-slate-800 align-top">
                                        {{ $poli->nama_poli }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-sm max-w-xs align-top">
                                        {{ $poli->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center align-top">
                                        @if($poli->dokters_count > 0)
                                            <span class="badge bg-blue-100 text-blue-700 border-blue-200 font-semibold px-3 py-1.5 rounded-full text-xs">
                                                {{ $poli->dokters_count }} Dokter
                                            </span>
                                        @else
                                            <span class="badge badge-ghost text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center text-slate-400">
                                        <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                        Belum ada data poli
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- Akses Cepat --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
            <div class="card-body p-0">

                {{-- Header --}}
                <div class="px-6 pt-5 pb-4 border-b border-base-200">
                    <h3 class="font-bold text-slate-700 text-base">Akses Cepat</h3>
                </div>

                {{-- Menu --}}
                <div class="p-4 space-y-2">

                    <a href="{{ route('polis.create') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-blue-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition">
                            <i class="fas fa-plus text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition">
                                Tambah Poli
                            </div>
                            <div class="text-xs text-slate-400">Daftarkan poli baru</div>
                        </div>
                    </a>

                    <a href="{{ route('dokter.create') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-emerald-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-200 transition">
                            <i class="fas fa-user-plus text-emerald-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-emerald-700 transition">
                                Tambah Dokter
                            </div>
                            <div class="text-xs text-slate-400">Daftarkan dokter baru</div>
                        </div>
                    </a>

                    <a href="{{ route('pasien.create') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-amber-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-200 transition">
                            <i class="fas fa-user-plus text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-amber-700 transition">
                                Tambah Pasien
                            </div>
                            <div class="text-xs text-slate-400">Daftarkan pasien baru</div>
                        </div>
                    </a>

                    <a href="{{ route('obat.create') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-pink-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-pink-100 flex items-center justify-center flex-shrink-0 group-hover:bg-pink-200 transition">
                            <i class="fas fa-plus text-pink-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-pink-700 transition">
                                Tambah Obat
                            </div>
                            <div class="text-xs text-slate-400">Tambahkan data obat baru</div>
                        </div>
                    </a>

                </div>

            </div>
        </div>

    </div>

</x-layouts.app>
