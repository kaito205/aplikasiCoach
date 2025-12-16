<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white">
            Halo, {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- JAM REAL-TIME --}}
            <div class="mb-6 text-center">
                <p class="text-gray-500 dark:text-gray-400 text-sm uppercas tracking-widest">Waktu Sekarang</p>
                <h2 id="realtime-clock" class="text-3xl font-mono font-bold text-gray-800 dark:text-white mt-1">
                    --:--:--
                </h2>
                <p id="realtime-date" class="text-blue-400 font-medium mt-1">
                    ...
                </p>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-gray-800 dark:text-white font-bold">🔥 Prototipe Aktif</h3>
                <button onclick="requestNotificationPermission()" id="btn-notify" class="text-xs bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-600">
                    🔔 Aktifkan Pengingat
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse ($prototypes as $prototype)
                <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-4 text-center shadow-sm dark:shadow-none">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-lg text-left text-gray-800 dark:text-white">
                            {{ $prototype->name }}
                        </h4>
                        
                        <form method="POST" action="{{ route('prototypes.destroy', $prototype->id) }}" onsubmit="return confirm('Yakin hapus prototipe ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 text-sm">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>

                    <p class="text-2xl font-bold mt-2 text-gray-800 dark:text-white">
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
                            <div class="mb-3">
                                <label class="block text-xs text-gray-500 dark:text-gray-300 text-left mb-1 ml-1">
                                    Input Tambahan (Contoh: 30 menit / 5 km)
                                </label>
                                <input type="number" name="quantity" placeholder="0" 
                                    class="w-full text-center bg-gray-50 border border-gray-300 text-gray-900 dark:bg-gray-900 dark:border-gray-600 dark:text-white rounded-md text-lg font-bold placeholder-gray-400 dark:placeholder-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2">
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
    
    <script>
        // Data Prototipe dari Controller
        @php
            $jsData = $prototypes->map(fn($p) => [
                'id' => $p->id, 
                'name' => $p->name, 
                'reminder_time' => $p->reminder_time ? substr($p->reminder_time, 0, 5) : null
            ])->values();
        @endphp
        const prototypes = {!! json_encode($jsData) !!};

        function requestNotificationPermission() {
            if (!("Notification" in window)) {
                alert("Browser ini tidak mendukung notifikasi desktop.");
                return;
            }

            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    alert("Notifikasi diaktifkan! Anda akan diingatkan sesuai jam prototipe.");
                    document.getElementById('btn-notify').style.display = 'none';
                    startReminderCheck(); // Mulai cek langsung
                }
            });
        }

        function startReminderCheck() {
            // Cek setiap 1 menit (60.000 ms)
            setInterval(() => {
                const now = new Date();
                const currentHours = String(now.getHours()).padStart(2, '0');
                const currentMinutes = String(now.getMinutes()).padStart(2, '0');
                const currentTime = `${currentHours}:${currentMinutes}`;
                
                console.log("Checking reminders for:", currentTime);

                prototypes.forEach(p => {
                    if (p.reminder_time === currentTime) {
                        showNotification(p.name);
                    }
                });
            }, 60000); 
        }
        
        function showNotification(prototypeName) {
            const notification = new Notification("Waktunya Check-in! ⏰", {
                body: `Jangan lupa update progres untuk prototipe: ${prototypeName}`,
                icon: "{{ asset('assets/img/logo.webp') }}"
            });
            
            notification.onclick = function() {
                window.focus();
                this.close();
            };
        }

        // Cek status izin saat load
        if (Notification.permission === "granted") {
             document.getElementById('btn-notify').style.display = 'none';
             startReminderCheck();
        }

        // JAM REAL-TIME
        let lastDateString = "";

        function updateClock() {
            const now = new Date();
            
            // Format Jam: 14:05:30
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false 
            });

            // Format Tanggal: Senin, 16 Desember 2025
            const dateString = now.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric' 
            });

            document.getElementById('realtime-clock').innerText = timeString;
            document.getElementById('realtime-date').innerText = dateString;

            // 🔄 Auto-Refresh saat ganti hari (Midnight Check)
            if (lastDateString !== "" && lastDateString !== dateString) {
                console.log("Hari berganti! Refreshing page...");
                location.reload(); 
            }
            lastDateString = dateString;
        }

        setInterval(updateClock, 1000);
        updateClock(); // Jalan pertama kali
    </script>
</x-app-layout>
