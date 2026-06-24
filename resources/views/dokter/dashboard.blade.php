<x-layouts.app title="Dashboard Dokter">

    {{-- ===== GREETING ===== --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Selamat Datang, {{ auth()->user()->nama ?? 'Dokter' }} 👋
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            {{ now()->translatedFormat('l, d F Y') }} — Berikut ringkasan aktivitas praktik Anda hari ini.
        </p>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        {{-- Total Jadwal --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-calendar-days text-blue-500 text-xl"></i>
                    </div>
                    <a href="{{ route('jadwal-periksa.index') }}"
                        class="text-xs font-semibold text-blue-500 hover:text-blue-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $totalJadwal }}</div>
                <div class="text-sm text-slate-500">Total Jadwal</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-200"></div>
        </div>

        {{-- Pasien Menunggu --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-users text-amber-500 text-xl"></i>
                    </div>
                    <a href="{{ route('periksa-pasien.index') }}"
                        class="text-xs font-semibold text-amber-500 hover:text-amber-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $pasienMenunggu }}</div>
                <div class="text-sm text-slate-500">Pasien Menunggu</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-400 to-amber-200"></div>
        </div>

        {{-- Total Riwayat --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center">
                        <i class="fas fa-file-medical text-pink-500 text-xl"></i>
                    </div>
                    <a href="{{ route('riwayat-pasien.index') }}"
                        class="text-xs font-semibold text-pink-500 hover:text-pink-700 transition">
                        Lihat
                    </a>
                </div>
                <div class="text-4xl font-bold text-slate-800 mb-1">{{ $totalRiwayat }}</div>
                <div class="text-sm text-slate-500">Total Riwayat</div>
            </div>
            <div class="h-1 bg-gradient-to-r from-pink-400 to-pink-200"></div>
        </div>

    </div>

    {{-- ===== BOTTOM SECTION ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- Tabel Jadwal Periksa --}}
        <div class="xl:col-span-2 card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
            <div class="card-body p-0">

                {{-- Header Tabel --}}
                <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-base-200">
                    <h3 class="font-bold text-slate-700 text-base">Jadwal Periksa</h3>
                    <a href="{{ route('jadwal-periksa.index') }}"
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
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Jam Mulai</th>
                                <th class="px-6 py-3">Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $jadwal)
                                <tr class="hover:bg-slate-50 transition border-b border-base-100 last:border-0">
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $jadwal->hari }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center text-slate-400">
                                        <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                        Belum ada jadwal periksa
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

                    <a href="{{ route('jadwal-periksa.create') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-blue-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition">
                            <i class="fas fa-plus text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition">
                                Tambah Jadwal
                            </div>
                            <div class="text-xs text-slate-400">Tambahkan jadwal periksa baru</div>
                        </div>
                    </a>

                    <a href="{{ route('periksa-pasien.index') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-amber-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-200 transition">
                            <i class="fas fa-stethoscope text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-amber-700 transition">
                                Periksa Pasien
                            </div>
                            <div class="text-xs text-slate-400">Lihat daftar pasien menunggu</div>
                        </div>
                    </a>

                    <a href="{{ route('riwayat-pasien.index') }}"
                        class="flex items-center gap-4 p-3 rounded-xl hover:bg-pink-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-pink-100 flex items-center justify-center flex-shrink-0 group-hover:bg-pink-200 transition">
                            <i class="fas fa-clock-rotate-left text-pink-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-pink-700 transition">
                                Riwayat Pasien
                            </div>
                            <div class="text-xs text-slate-400">Lihat riwayat pemeriksaan</div>
                        </div>
                    </a>

                </div>

            </div>
        </div>

    </div>

</x-layouts.app>
