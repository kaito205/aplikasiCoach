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

            {{-- LINK KE MODE FOKUS --}}
            <a href="{{ route('focus') }}" class="block mb-8 group">
                <div class="p-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg text-white relative overflow-hidden ring-1 ring-white/20 transition-transform transform group-hover:scale-[1.02]">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-black/10 blur-xl"></div>

                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <span class="text-2xl">⏱️</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl">Mode Fokus</h3>
                                <p class="text-indigo-100 text-sm">Masuk ke ruang fokus dengan visualisasi tanaman.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-white text-indigo-600 rounded-full flex items-center justify-center shadow-lg group-hover:bg-indigo-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

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
                    {{-- KARTU BERDASARKAN TIPE --}}
                    @if ($prototype->type == 'sleep')
                        {{-- KARTU TIDUR --}}
                        <div class="mt-4 bg-purple-900/10 rounded-lg p-4 text-center border border-purple-100">
                           <div class="text-4xl mb-2">😴</div>
                           <p class="text-sm font-bold text-purple-800 dark:text-purple-300">Target Tidur</p>
                           <h3 class="text-2xl font-bold font-mono text-purple-900 dark:text-purple-100">
                               {{ $prototype->settings['target_time'] ?? '22:00' }}
                           </h3>
                           
                           @if ($prototype->today_log)
                                <div class="mt-3 px-3 py-2 bg-green-100 text-green-700 rounded text-sm font-bold border border-green-200">
                                    ✅ Tercatat: {{ $prototype->today_log->created_at->format('H:i') }}
                                </div>
                           @else
                                <form method="POST" action="{{ route('daily-logs.store') }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="prototype_id" value="{{ $prototype->id }}">
                                    <input type="hidden" name="success" value="1">
                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform active:scale-95">
                                        🛌 Tidur Sekarang
                                    </button>
                                </form>
                           @endif
                        </div>

                    @elseif ($prototype->type == 'study')
                         {{-- KARTU BELAJAR --}}
                         <div class="mt-4 bg-indigo-900/10 rounded-lg p-4 text-center border border-indigo-100">
                            <div class="text-4xl mb-2">📚</div>
                            <p class="text-sm font-bold text-indigo-800 dark:text-indigo-300">Sesi Fokus</p>
                            
                            @if ($prototype->today_log)
                                 <div class="mt-2 px-3 py-1 bg-green-100 text-green-700 rounded text-sm font-bold border border-green-200 inline-block mb-2">
                                     ✅ Selesai Hari Ini
                                 </div>
                            @endif

                             <a href="{{ route('focus') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform active:scale-95 mt-2">
                                 ⏱️ Mulai Fokus
                             </a>
                             
                             @if (!$prototype->today_log)
                                <form method="POST" action="{{ route('daily-logs.store') }}" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="prototype_id" value="{{ $prototype->id }}">
                                    <button type="submit" name="success" value="1" class="text-xs text-indigo-500 underline hover:text-indigo-700">
                                        Check-in manual
                                    </button>
                                </form>
                             @endif
                         </div>

                    @elseif ($prototype->type == 'checklist')
                        {{-- KARTU CHECKLIST (misal: Makan 3x) --}}
                         <div class="mt-4 bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-100 dark:border-green-800">
                             <div class="flex justify-between items-center mb-2">
                                 <div class="text-sm font-bold text-green-800 dark:text-green-300">Progress Harian</div>
                                 <div class="text-xs bg-white dark:bg-gray-800 border px-2 py-1 rounded">
                                     {{-- Simple count based on multiple logs could be implemented here, simplified for now --}}
                                     {{ $prototype->today_log ? '1' : '0' }} / {{ $prototype->settings['target_count'] ?? 1 }}
                                 </div>
                             </div>
                             
                             @if ($prototype->today_log)
                                  <div class="w-full bg-green-200 rounded-full h-2.5 dark:bg-green-700 mt-2">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: 100%"></div>
                                  </div>
                                  <div class="mt-2 text-center text-sm text-green-700 font-bold">Selesai! 🥙</div>
                             @else
                                <form method="POST" action="{{ route('daily-logs.store') }}" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="prototype_id" value="{{ $prototype->id }}">
                                    <button type="submit" name="success" value="1" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm">
                                        ✅ Check (Selesai)
                                    </button>
                                </form>
                             @endif
                         </div>

                    @else
                        {{-- KARTU STANDARD (DEFAULT) --}} 
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
            // 1. Tampilkan Overlay (Hanya jika Reminder Prototipe / Check-in)
            if (isPrototype) {
                const overlay = document.getElementById('reminder-overlay');
                document.getElementById('overlay-message').innerText = "Prototipe: " + name;
                overlay.classList.remove('hidden');
            }

            // 2. Mainkan Suara
            const audio = document.getElementById('alarm-sound');
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio play failed (user interaction needed first):", e));

            // 3. Getar (Vibrate) - Pola seperti telepon berdering
            if (navigator.vibrate) {
                navigator.vibrate([1000, 500, 1000, 500, 1000]); // Getar 1d, diam 0.5d, ulang
            }

            // 4. Notifikasi Sistem
            // Jika Timer (bukan prototipe), SELALU kirim notifikasi sitem.
            // Jika Prototipe, hanya kirim jika tab hidden (karena overlay sudah muncul).
            if (Notification.permission === "granted") {
                if (!isPrototype || document.hidden) {
                    new Notification(isPrototype ? "📞 Waktunya Check-in: " + name : "⏰ Waktu Habis!", {
                        body: isPrototype ? "Klik untuk membuka aplikasi." : name,
                        requireInteraction: true, 
                        tag: isPrototype ? 'reminder' : 'timer-done',
                        renotify: true
                    });
                }
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
