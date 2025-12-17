<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Mode Fokus') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden relative flex flex-col items-center justify-center p-4">
        
        {{-- Background Gradient for Ambience --}}
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/50 to-white dark:from-gray-800 dark:to-gray-900 z-0 pointer-events-none"></div>

        <div class="z-10 w-full max-w-md text-center">
            
            {{-- FLOWER ANIMATION CONTAINER --}}
            <div class="relative w-64 h-80 mx-auto mb-8 flex items-end justify-center pointer-events-none">
                {{-- Tanah / Pot --}}
                <div class="absolute bottom-0 w-32 h-4 bg-gray-800/10 dark:bg-white/10 rounded-full blur-sm"></div>
                
                <svg id="flower-svg" width="200" height="300" viewBox="0 0 200 300" class="drop-shadow-xl pointer-events-none" style="overflow: visible;">
                    <!-- Stem (Batang) -->
                    <!-- Path: Start bottom-center (100, 300), curve up to (100, 100) -->
                    <path id="stem" d="M100 300 Q100 200 100 100" 
                        stroke="#22c55e" stroke-width="4" fill="none" stroke-linecap="round"
                        stroke-dasharray="210" stroke-dashoffset="210"
                        class="transition-all duration-1000 ease-linear" />
                    
                    <!-- Leaves (Daun) -->
                    <g id="leaves" class="opacity-0 transition-opacity duration-700">
                        <path id="leaf-left" d="M100 220 Q60 210 50 180 Q80 200 100 210" fill="#4ade80" transform="scale(0)" style="transform-origin: 100px 220px; transition: transform 0.5s ease-out;" />
                        <path id="leaf-right" d="M100 170 Q140 160 150 130 Q120 150 100 160" fill="#4ade80" transform="scale(0)" style="transform-origin: 100px 170px; transition: transform 0.5s ease-out;" />
                    </g>

                    <!-- Flower Head (Kepala Bunga) -->
                    <g id="flower-head" transform="translate(100, 100) scale(0)" style="transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);">
                        <!-- Petals -->
                        <g id="petals" class="animate-spin-slow">
                             <!-- 8 Petals arranged in a circle -->
                            @for ($i = 0; $i < 8; $i++)
                                <ellipse cx="0" cy="-30" rx="10" ry="25" fill="#f472b6" transform="rotate({{ $i * 45 }})" />
                            @endfor
                        </g>
                        <!-- Center -->
                        <circle cx="0" cy="0" r="15" fill="#fbbf24" stroke="#f59e0b" stroke-width="2" />
                    </g>
                </svg>

                {{-- Status Text (Motivational) --}}
                <div id="flower-message" class="absolute -bottom-10 w-full text-center text-sm font-medium text-gray-500 dark:text-gray-400 opacity-0 transition-opacity duration-500">
                    Menunggu benih...
                </div>
            </div>

            {{-- TIMER DISPLAY --}}
            <div class="mb-10">
                <div id="timer-display" class="text-7xl font-mono font-bold text-gray-800 dark:text-white tabular-nums tracking-tighter transition-colors duration-300">
                    25:00
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm uppercase tracking-widest mt-2">Waktu Tersisa</p>
            </div>

            {{-- CONTROLS --}}
            <div class="flex flex-col items-center gap-6 relative z-50">
                
                {{-- Main Button --}}
                <button type="button" onclick="toggleFokus()" id="btn-toggle" 
                    class="w-20 h-20 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center shadow-lg hover:shadow-indigo-500/50 transition-all duration-300 transform active:scale-95 group">
                    <svg id="icon-play" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 ml-1 group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                    </svg>
                    <svg id="icon-pause" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 hidden group-hover:scale-110 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                    </svg>
                </button>

                {{-- Secondary Controls --}}
                <div class="flex items-center gap-3">
                    <button type="button" onclick="changeDuration(25)" class="px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        25m
                    </button>
                    <button type="button" onclick="changeDuration(45)" class="px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        45m
                    </button>
                    <button type="button" onclick="changeDuration(60)" class="px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        60m
                    </button>
                    <button type="button" onclick="resetFokus()" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition" title="Reset">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>

                {{-- Custom Input --}}
                <div class="flex items-center gap-2">
                     <input type="number" id="custom-min" placeholder="Menit" min="1" max="180" 
                        onkeypress="if(event.key === 'Enter') setCustomDuration()"
                        class="w-24 px-3 py-2 text-center rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm placeholder-gray-400">
                    <button type="button" onclick="setCustomDuration()" class="px-3 py-2 rounded-lg bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-sm font-medium transition shadow-sm">
                        Set
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- AUDIO for Timer End --}}
    <audio id="focus-alarm">
        <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.m4a" type="audio/mp4">
    </audio>

    <style>
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 12s linear infinite;
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <script>
        let duration = 25 * 60;
        let timeLeft = duration;
        let interval = null;
        let isRunning = false;
        
        // DOM Elements
        const timerDisplay = document.getElementById('timer-display');
        const btnToggle = document.getElementById('btn-toggle');
        const iconPlay = document.getElementById('icon-play');
        const iconPause = document.getElementById('icon-pause');
        const alarm = document.getElementById('focus-alarm');
        
        // Flower Elements
        const stem = document.getElementById('stem');
        const leafLeft = document.getElementById('leaf-left');
        const leafRight = document.getElementById('leaf-right');
        const flowerHead = document.getElementById('flower-head');
        const flowerMessage = document.getElementById('flower-message');

        // Stem length logic
        // Path length is around 210
        const stemLength = 210;

        function updateDisplay() {
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            timerDisplay.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            document.title = `(${m}:${s.toString().padStart(2, '0')}) Mode Fokus`;
            
            updateFlower();
        }

        function updateFlower() {
            // Calculate progress (0 to 1)
            // 0: Start, 1: Finish
            const progress = 1 - (timeLeft / duration);
            
            // 1. Stem Growth (0% to 50%)
            // stroke-dashoffset: 210 (hidden) -> 0 (fully visible)
            // We want it fully grown by 50% progress
            let stemProgress = progress / 0.5; // 0 to 1 over first half
            if (stemProgress > 1) stemProgress = 1;
            const offset = stemLength - (stemLength * stemProgress);
            stem.style.strokeDashoffset = offset;

            // 2. Leaves (Appear at 30% and 40%)
            if (progress > 0.3) {
                leafLeft.style.transform = 'scale(1)';
                document.getElementById('leaves').classList.remove('opacity-0');
            } else {
                leafLeft.style.transform = 'scale(0)';
            }

            if (progress > 0.4) {
                leafRight.style.transform = 'scale(1)';
            } else {
                leafRight.style.transform = 'scale(0)';
            }

            // 3. Flower Blooming (Start at 60%, Full at 100%)
            if (progress > 0.6) {
                // scale 0 to 1 between 60% and 100%
                let bloomProgress = (progress - 0.6) / 0.4;
                flowerHead.style.transform = `translate(100px, 100px) scale(${bloomProgress})`;
            } else {
                flowerHead.style.transform = `translate(100px, 100px) scale(0)`;
            }

            // Messages
            flowerMessage.classList.remove('opacity-0');
            if (progress < 0.1) flowerMessage.innerText = "Mulai menanam...";
            else if (progress < 0.5) flowerMessage.innerText = "Tumbuh perlahan...";
            else if (progress < 0.8) flowerMessage.innerText = "Hampir mekar...";
            else if (progress < 1) flowerMessage.innerText = "Sedikit lagi...";
            else flowerMessage.innerText = "Bunga mekar sempurna! 🌸";
        }

        function toggleFokus() {
            if (isRunning) {
                pauseTimer();
            } else {
                startTimer();
            }
        }

        function startTimer() {
            if (timeLeft <= 0) return;
            isRunning = true;
            iconPlay.classList.add('hidden');
            iconPause.classList.remove('hidden');
            btnToggle.classList.remove('bg-indigo-600', 'hover:bg-indigo-500');
            btnToggle.classList.add('bg-yellow-500', 'hover:bg-yellow-400'); // Pause color
            
            interval = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    updateDisplay();
                } else {
                    finishTimer();
                }
            }, 1000);
        }

        function pauseTimer() {
            isRunning = false;
            clearInterval(interval);
            iconPlay.classList.remove('hidden');
            iconPause.classList.add('hidden');
            btnToggle.classList.add('bg-indigo-600', 'hover:bg-indigo-500');
            btnToggle.classList.remove('bg-yellow-500', 'hover:bg-yellow-400');
        }

        function finishTimer() {
            pauseTimer();
            alarm.play();
            timerDisplay.classList.add('text-green-500', 'animate-pulse');
            
            // Send notification
            if (Notification.permission === "granted") {
                new Notification("Sesi Fokus Selesai!", {
                    body: "Bunga Anda telah mekar sempurna! 🌸",
                    icon: "https://cdn-icons-png.flaticon.com/512/7479/7479439.png"
                });
            }
        }

        function resetFokus() {
            pauseTimer();
            timeLeft = duration;
            updateDisplay();
            timerDisplay.classList.remove('text-green-500', 'animate-pulse');
            flowerMessage.classList.add('opacity-0');
            document.title = "Mode Fokus";
        }

        function changeDuration(minutes) {
            duration = minutes * 60;
            resetFokus();
        }

        function setCustomDuration() {
            const input = document.getElementById('custom-min');
            const val = parseInt(input.value);
            if (val && val > 0) {
                changeDuration(val);
                input.value = '';
            } else {
                alert('Mohon masukkan jumlah menit yang valid (contoh: 25)');
            }
        }

        // Initialize Display
        // Add stroke-dasharray to CSS via JS to ensure it works cross-browser
        stem.style.strokeDasharray = stemLength;
        stem.style.strokeDashoffset = stemLength; // Start hidden
        
        // Request Notification Permission on interact
        document.body.addEventListener('click', () => {
             if (Notification.permission !== "granted") {
                 Notification.requestPermission();
             }
        }, { once: true });

    </script>
</x-app-layout>
