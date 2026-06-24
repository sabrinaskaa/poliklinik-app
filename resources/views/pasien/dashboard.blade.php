<x-layouts.app title="Pasien Dashboard">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-slate-800">
            Halo, 👋
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Selamat datang {{ auth()->user()->nama ?? 'Pasien' }} di dashboard Pasien!
        </p>
    </div>
</x-layouts.app>