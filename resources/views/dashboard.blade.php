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
                    <h4 class="font-semibold text-lg">
                        {{ $prototype->name }}
                    </h4>

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

                            <div class="flex justify-center gap-2">
                                <button type="submit" name="success" value="1"
                                    class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    ✔
                                </button>

                                <button type="submit" name="success" value="0"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                    ✖
                                </button>
                            </div>
                        </form>
                    @endif
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
