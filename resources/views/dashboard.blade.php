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

            {{-- TIMER BELAJAR / FOKUS --}}
            <div class="mb-8 p-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg text-white text-center relative overflow-hidden ring-1 ring-white/20">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-black/10 blur-xl"></div>

                <h3 class="font-bold text-lg uppercase tracking-wider mb-4 flex items-center justify-center gap-2">
                    ⏱️ Mode Fokus
                </h3>
                
                <!-- Timer Display -->
                <div class="text-5xl md:text-6xl font-mono font-bold mb-6 tabular-nums tracking-tighter" id="timer-display">
                    25:00
                </div>

                <!-- Controls -->
                <div class="flex justify-center gap-4 mb-6 relative z-10">
                    <button onclick="toggleTimer()" id="btn-start-pause" class="bg-white text-indigo-600 font-bold py-2 px-8 rounded-full hover:bg-gray-100 transition shadow-lg hover:shadow-xl active:scale-95 flex items-center gap-2">
                        <span id="icon-play">▶</span> <span id="text-start-pause">Mulai</span>
                    </button>
                    <button onclick="resetTimer()" class="bg-white/20 hover:bg-white/30 text-white font-bold py-2 px-4 rounded-full transition backdrop-blur-sm active:scale-95 border border-white/10">
                        🔄 Reset
                    </button>
                </div>

                <!-- Presets -->
                <div class="flex justify-center gap-2 relative z-10 flex-wrap">
                    <button onclick="setTimer(25)" class="px-4 py-1.5 bg-black/20 hover:bg-black/30 rounded-full text-sm transition border border-white/10 backdrop-blur-md">25 Menit</button>
                    <button onclick="setTimer(5)" class="px-4 py-1.5 bg-black/20 hover:bg-black/30 rounded-full text-sm transition border border-white/10 backdrop-blur-md">5 Menit</button>
                    <button onclick="setTimer(60)" class="px-4 py-1.5 bg-black/20 hover:bg-black/30 rounded-full text-sm transition border border-white/10 backdrop-blur-md">60 Menit</button>
                </div>
                
                <!-- Custom Time -->
                <div class="mt-4 flex justify-center items-center gap-2 relative z-10">
                    <input type="number" id="custom-minutes" placeholder="Menit" min="1" max="180" 
                        class="w-20 px-3 py-1 text-sm bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 text-center spin-hide">
                    <button onclick="setCustomTimer()" class="px-3 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition border border-white/10 font-bold">
                        Set Custom
                    </button>
                </div>
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
    
    {{-- OVERLAY PENGINGAT (ALA PANGGILAN MODERN) --}}
    <div id="reminder-overlay" class="fixed inset-0 bg-gray-900/95 backdrop-blur-sm hidden z-50 flex flex-col items-center justify-between py-20 px-6 text-white transition-opacity duration-300">
        
        {{-- Top Section: Caller Info --}}
        <div class="flex flex-col items-center text-center mt-10">
            <div class="animate-bounce mb-6">
                <div class="w-28 h-28 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-[0_0_50px_rgba(59,130,246,0.5)] border-4 border-gray-800">
                    <span class="text-5xl">⏰</span>
                </div>
            </div>
            <h2 class="text-xl font-medium tracking-wide text-gray-400 uppercase">Pengingat Masuk...</h2>
            <h1 id="overlay-message" class="text-4xl md:text-5xl font-bold mt-4 leading-tight">???</h1>
            <p class="mt-2 text-gray-300">Waktunya untuk check-in progress!</p>
        </div>

        {{-- Bottom Section: Actions --}}
        <div class="w-full max-w-sm">
            <div class="flex justify-between items-center w-full px-8">
                
                {{-- Decline Button --}}
                <div class="flex flex-col items-center gap-2 group">
                    <button onclick="dismissReminder()" class="w-16 h-16 bg-red-500/20 text-red-500 border-2 border-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <span class="text-sm font-medium text-gray-400 group-hover:text-red-400 transition-colors">Tutup</span>
                </div>

                {{-- Accept Button (Check-In) --}}
                <div class="flex flex-col items-center gap-2 group">
                    <a href="{{ route('dashboard') }}" onclick="dismissReminder()" class="relative w-20 h-20 bg-green-500 rounded-full flex items-center justify-center text-white hover:bg-green-400 transition-all duration-300 transform group-hover:scale-110 shadow-[0_0_40px_rgba(34,197,94,0.6)] animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </a>
                    <span class="text-sm font-medium text-gray-400 group-hover:text-green-400 transition-colors">Check-In</span>
                </div>

            </div>
        </div>
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
        
        function triggerAlarm(name, isPrototype = true) {
            // 1. Tampilkan Overlay (Layar Penuh)
            const overlay = document.getElementById('reminder-overlay');
            document.getElementById('overlay-message').innerText = isPrototype ? "Prototipe: " + name : name;
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

        // --- TIMER FOKUS LOGIC ---
        let timerInterval;
        let currentDuration = 25 * 60; // Default 25 menit
        let timeLeft = currentDuration; 
        let isTimerRunning = false;

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const display = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            document.getElementById('timer-display').innerText = display;
            
            // Update Tab Title
            if (isTimerRunning) {
                document.title = `(${display}) Mode Fokus`;
            } else {
                document.title = "Prototype Coach";
            }
        }

        function toggleTimer() {
            const btn = document.getElementById('btn-start-pause');
            const icon = document.getElementById('icon-play');
            const text = document.getElementById('text-start-pause');

            if (isTimerRunning) {
                // Pause
                clearInterval(timerInterval);
                isTimerRunning = false;
                icon.innerText = "▶";
                text.innerText = "Lanjut";
                btn.classList.remove('bg-yellow-400', 'text-yellow-900');
                btn.classList.add('bg-white', 'text-indigo-600');
            } else {
                // Start
                if (timeLeft <= 0) return;
                
                isTimerRunning = true;
                icon.innerText = "⏸";
                text.innerText = "Jeda";
                btn.classList.remove('bg-white', 'text-indigo-600');
                btn.classList.add('bg-yellow-400', 'text-yellow-900');

                timerInterval = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateTimerDisplay();
                    } else {
                        // Timer Selesai
                        clearInterval(timerInterval);
                        isTimerRunning = false;
                        triggerAlarm("Waktu Fokus Selesai!", false);
                        icon.innerText = "▶";
                        text.innerText = "Mulai";
                        btn.classList.remove('bg-yellow-400', 'text-yellow-900');
                        btn.classList.add('bg-white', 'text-indigo-600');
                        timeLeft = 0;
                        updateTimerDisplay();
                        document.title = "Waktu Habis!";
                    }
                }, 1000);
            }
        }

        function resetTimer() {
            clearInterval(timerInterval);
            isTimerRunning = false;
            timeLeft = currentDuration;
            updateTimerDisplay();
            
            const btn = document.getElementById('btn-start-pause');
            document.getElementById('icon-play').innerText = "▶";
            document.getElementById('text-start-pause').innerText = "Mulai";
            btn.classList.remove('bg-yellow-400', 'text-yellow-900');
            btn.classList.add('bg-white', 'text-indigo-600');
            document.title = "Prototype Coach";
        }

        function setTimer(minutes) {
            clearInterval(timerInterval);
            isTimerRunning = false;
            currentDuration = minutes * 60;
            timeLeft = currentDuration;
            updateTimerDisplay();

            const btn = document.getElementById('btn-start-pause');
            document.getElementById('icon-play').innerText = "▶";
            document.getElementById('text-start-pause').innerText = "Mulai";
            btn.classList.remove('bg-yellow-400', 'text-yellow-900');
            btn.classList.add('bg-white', 'text-indigo-600');
            document.title = "Prototype Coach";
        }

        function setCustomTimer() {
            const input = document.getElementById('custom-minutes');
            const minutes = parseInt(input.value);
            
            if (minutes && minutes > 0) {
                setTimer(minutes);
                input.value = ""; 
            } else {
                alert("Masukkan jumlah menit yang valid!");
            }
        }
    </script>
</x-app-layout>
