<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            👋 Halo, Bro {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h3 class="text-white font-bold mb-4">🔥 Prototipe Aktif</h3>

            <div class="text-white grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse ($prototypes as $prototype)
                <div class="border rounded-lg p-4 text-center">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-lg text-left">
                            {{ $prototype->name }}
                        </h4>
                        
                        <form method="POST" action="{{ route('prototypes.destroy', $prototype->id) }}" onsubmit="return confirm('Yakin hapus prototipe ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-200 text-sm">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>

                    <p class="text-2xl font-bold mt-2">
                        {{ $prototype->success_rate }}%
                    </p>

                    {{-- FORM CHECK-IN --}}
                    {{-- STATUS CHECK-IN HARI INI --}}
                    @if ($prototype->today_log)
                        <div class="mt-3">
                            @if ($prototype->today_log->success)
                                <div class="px-3 py-2 bg-green-600/50 border border-green-500 text-green-100 rounded cursor-not-allowed">
                                    ✅ Berhasil hari ini
                                </div>
                            @else
                                <div class="px-3 py-2 bg-red-600/50 border border-red-500 text-red-100 rounded cursor-not-allowed">
                                    ❌ Gagal hari ini
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- FORM CHECK-IN --}}
                        <form method="POST" action="{{ route('daily-logs.store') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="prototype_id" value="{{ $prototype->id }}">

                            {{-- Input Kuantitatif (Optional) --}}
                            <div class="mb-2">
                                <input type="number" name="quantity" placeholder="Jml (Opsional)" 
                                    class="w-full text-center bg-gray-700 border-none rounded text-white text-sm placeholder-gray-500 focus:ring-1 focus:ring-blue-500 py-1">
                            </div>

                            <div class="flex justify-center gap-2">
                                <button type="submit" name="success" value="1"
                                    class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition w-full">
                                    ✔ Berhasil
                                </button>

                                <button type="submit" name="success" value="0"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition w-full">
                                    ✖ Gagal
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- SARAN ITERASI --}}
                    @if ($prototype->suggestion)
                        <div class="mt-3 px-3 py-2 rounded text-sm font-semibold 
                            {{ $prototype->suggestion['type'] == 'upgrade' ? 'bg-blue-600/30 text-blue-200 border border-blue-500/50' : 'bg-orange-600/30 text-orange-200 border border-orange-500/50' }}">
                            {{ $prototype->suggestion['message'] }}
                        </div>
                    @endif

                    {{-- MINGGU INI (MINGGUAN) --}}
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide">Minggu Ini</p>
                        <div class="flex justify-between items-center text-xs text-center">
                            @foreach ($prototype->weekly_progress as $day)
                                <div class="flex flex-col items-center gap-1">
                                    {{-- Status Box --}}
                                    @if ($day['status'] == 'success')
                                        <div class="w-6 h-6 rounded bg-green-500 flex items-center justify-center text-white" title="{{ $day['date'] }}: Berhasil">✔</div>
                                    @elseif ($day['status'] == 'failed')
                                        <div class="w-6 h-6 rounded bg-red-500 flex items-center justify-center text-white" title="{{ $day['date'] }}: Gagal">✖</div>
                                    @elseif ($day['status'] == 'future')
                                        <div class="w-6 h-6 rounded bg-gray-700/50 border border-dashed border-gray-500" title="{{ $day['date'] }}: Belum saatnya"></div>
                                    @else
                                        <div class="w-6 h-6 rounded bg-gray-600" title="{{ $day['date'] }}: Belum diisi"></div>
                                    @endif

                                    {{-- Day Name --}}
                                    <span class="{{ $day['is_today'] ? 'text-yellow-400 font-bold' : 'text-gray-400' }}">
                                        {{ substr($day['day_name'], 0, 1) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">Belum ada prototipe</p>
                @endforelse
            </div>

            <a href="{{ route('prototypes.create') }}"
                class="inline-block mt-6 bg-blue-600 text-white px-4 py-2 rounded">
                ➕ Tambah Prototipe
            </a>

        </div>
    </div>
</x-app-layout>
