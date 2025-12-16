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
    
    {{-- OVERLAY PENGINGAT (ALA PANGGILAN) --}}
    <div id="reminder-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-95 hidden z-50 flex flex-col items-center justify-center text-white">
        <div class="animate-pulse mb-8">
            <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center mx-auto shadow-[0_0_30px_rgba(37,99,235,0.8)]">
                <span class="text-4xl">⏰</span>
            </div>
        </div>
        
        <h2 class="text-3xl font-bold mb-2">Waktunya Check-in!</h2>
        <p id="overlay-message" class="text-xl text-gray-300 mb-12">Prototipe: ???</p>

        <div class="flex gap-6">
            <button onclick="dismissReminder()" class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center hover:bg-red-700 transition transform hover:scale-110">
                ✖
            </button>
            <a href="{{ route('dashboard') }}" onclick="dismissReminder()" class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center hover:bg-green-700 transition transform hover:scale-110">
                ✔
            </a>
        </div>
         <p class="mt-4 text-sm text-gray-500">Abaikan / Buka Aplikasi</p>
    </div>

    {{-- AUDIO: Simple Classic Alarm Sound (Base64 for reliability) --}}
    <audio id="alarm-sound" loop>
        <source src="https://actions.google.com/sounds/v1/alarms/alarm_clock.ogg" type="audio/ogg">
    </audio>

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
                    alert("Notifikasi diaktifkan! Browser harus tetap terbuka agar alarm berbunyi.");
                    document.getElementById('btn-notify').style.display = 'none';
                    startReminderCheck(); // Mulai cek
                    
                    // Test sound (optional, to unlock audio context)
                    // document.getElementById('alarm-sound').play().then(() => document.getElementById('alarm-sound').pause());
                }
            });
        }

        function startReminderCheck() {
            setInterval(() => {
                const now = new Date();
                const currentHours = String(now.getHours()).padStart(2, '0');
                const currentMinutes = String(now.getMinutes()).padStart(2, '0');
                const currentTime = `${currentHours}:${currentMinutes}`;
                
                console.log("Checking reminders for:", currentTime);

                prototypes.forEach(p => {
                    if (p.reminder_time === currentTime) {
                        triggerAlarm(p.name);
                    }
                });
            }, 60000); // 1 menit
        }
        
        function triggerAlarm(name) {
            // 1. Tampilkan Overlay (Layar Penuh)
            const overlay = document.getElementById('reminder-overlay');
            document.getElementById('overlay-message').innerText = "Prototipe: " + name;
            overlay.classList.remove('hidden');

            // 2. Mainkan Suara
            const audio = document.getElementById('alarm-sound');
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio play failed (user interaction needed first):", e));

            // 3. Getar (Vibrate) - Pola seperti telepon berdering
            if (navigator.vibrate) {
                navigator.vibrate([1000, 500, 1000, 500, 1000]); // Getar 1d, diam 0.5d, ulang
            }

            // 4. Notifikasi Sistem (Cadangan jika tidak sedang buka tab)
            if (document.hidden && Notification.permission === "granted") {
                new Notification("📞 Waktunya Check-in: " + name, {
                    body: "Klik untuk membuka aplikasi.",
                    requireInteraction: true, // Tidak hilang sendiri
                    tag: 'reminder',
                    renotify: true
                });
            }
        }

        function dismissReminder() {
            // Sembunyikan Overlay & Matikan Suara
            document.getElementById('reminder-overlay').classList.add('hidden');
            document.getElementById('alarm-sound').pause();
            if (navigator.vibrate) navigator.vibrate(0); // Stop getar
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
