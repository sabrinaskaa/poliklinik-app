<x-layouts.app title="Periksa Pasien">
    <div>
        <h1 class="text-2xl font-bold mb-6">Periksa Pasien</h1>

        @if (session('message'))
            <div id="alert-success" class="mb-4 px-4 py-3 rounded bg-green-500 text-white">
                {{ session('message') }}
            </div>
            <script>
                setTimeout(() => {
                    const alertBox = document.getElementById('alert-success');
                    if (alertBox) alertBox.style.display = 'none';
                }, 3000);
            </script>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="table w-full">
                <thead class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Pasien</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">No Antrian</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->keluhan }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->no_antrian }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('dokter.periksa-pasien.edit', $item->id) }}"
                                    class="px-4 py-2 bg-yellow-500 text-white rounded">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-3xl mb-3"></i>
                                    <span>Belum ada data jadwal periksa</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
