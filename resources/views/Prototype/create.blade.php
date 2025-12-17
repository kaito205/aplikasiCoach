<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            ➕ Tambah Prototipe Baru
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg transition-colors">

            <div class="mb-8 text-center">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Mulai Kebiasaan Baru</h3>
                <p class="text-gray-600 dark:text-gray-400">Pilih jenis aktivitas yang ingin kamu bangun.</p>
            </div>

            <form method="POST" action="{{ route('prototypes.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Kategori Aktivitas</label>
                    <div class="grid grid-cols-2 gap-4" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem;">
                        
                        {{-- STANDARD --}}
                        <label id="card-standard" class="category-card cursor-pointer border rounded-xl p-4 text-center transition-all duration-300 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:shadow-md">
                            <input type="radio" name="type" value="standard" class="hidden" checked onclick="toggleSettings('standard')">
                            <div class="w-12 h-12 mx-auto bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400 rounded-full flex items-center justify-center text-2xl mb-2">📝</div>
                            <span class="block font-bold text-gray-800 dark:text-white">Reguler</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Ceklist harian sederhana</span>
                        </label>

                        {{-- STUDY --}}
                        <label id="card-study" class="category-card cursor-pointer border rounded-xl p-4 text-center transition-all duration-300 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:shadow-md">
                            <input type="radio" name="type" value="study" class="hidden" onclick="toggleSettings('study')">
                            <div class="w-12 h-12 mx-auto bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400 rounded-full flex items-center justify-center text-2xl mb-2">📚</div>
                            <span class="block font-bold text-gray-800 dark:text-white">Fokus</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Timer & visualisasi</span>
                        </label>

                        {{-- SLEEP --}}
                        <label id="card-sleep" class="category-card cursor-pointer border rounded-xl p-4 text-center transition-all duration-300 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:shadow-md">
                            <input type="radio" name="type" value="sleep" class="hidden" onclick="toggleSettings('sleep')">
                            <div class="w-12 h-12 mx-auto bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400 rounded-full flex items-center justify-center text-2xl mb-2">😴</div>
                            <span class="block font-bold text-gray-800 dark:text-white">Tidur</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Target jam istirahat</span>
                        </label>

                        {{-- CHECKLIST --}}
                        <label id="card-checklist" class="category-card cursor-pointer border rounded-xl p-4 text-center transition-all duration-300 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:shadow-md">
                            <input type="radio" name="type" value="checklist" class="hidden" onclick="toggleSettings('checklist')">
                            <div class="w-12 h-12 mx-auto bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-400 rounded-full flex items-center justify-center text-2xl mb-2">🥗</div>
                            <span class="block font-bold text-gray-800 dark:text-white">Rutinitas</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Frekuensi harian</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aktivitas</label>
                    <input type="text" name="name"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white text-gray-900 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                        placeholder="Contoh: Baca Buku 30 Menit"
                        required>
                </div>

                {{-- DYNAMIC SETTINGS --}}
                <div id="settings-sleep" class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800 rounded-lg hidden animate-fade-in-down">
                    <label class="block font-medium text-purple-900 dark:text-purple-300 text-sm mb-1">Target Jam Tidur</label>
                    <input type="time" name="settings[target_time]" class="w-full rounded-lg border-purple-200 dark:border-purple-700 dark:bg-purple-900/50 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                    <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Kami akan mengingatkanmu untuk bersiap tidur.</p>
                </div>
                
                <div id="settings-checklist" class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-lg hidden animate-fade-in-down">
                    <label class="block font-medium text-green-900 dark:text-green-300 text-sm mb-1">Target Frekuensi (Kali per hari)</label>
                    <div class="flex items-center gap-4">
                        <input type="range" name="settings[target_count]" value="3" min="1" max="10" 
                            oninput="document.getElementById('freq-val').innerText = this.value + 'x'"
                            class="w-full h-2 bg-green-200 rounded-lg appearance-none cursor-pointer dark:bg-green-700">
                        <span id="freq-val" class="font-bold text-green-700 dark:text-green-400 min-w-[30px]">3x</span>
                    </div>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">Geser untuk menentukan jumlah target.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi & Motivasi (Opsional)</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white text-gray-900 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="Tulis alasan kenapa kamu ingin membangun kebiasaan ini..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white text-gray-900 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            value="{{ date('Y-m-d') }}"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ingatkan Pada Pukul</label>
                        <input type="time" name="reminder_time"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white text-gray-900 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" style="border-color: #d1d5db; color: #374151;"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Batal
                    </a>

                    <button type="submit" style="background-color: #4f46e5; color: white;"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition transform active:scale-95">
                        Simpan & Mulai
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

<script>
    function toggleSettings(type) {
        // Reset all active states
        document.querySelectorAll('.category-card').forEach(el => {
            el.style.borderColor = '#e5e7eb'; // gray-200
            el.style.backgroundColor = ''; // reset
            el.style.borderWidth = '1px';
            el.classList.remove('ring-2', 'ring-indigo-500'); // Remove ring classes just in case
        });

        // Set active state for clicked card
        const activeCard = document.getElementById('card-' + type);
        if(activeCard) {
            activeCard.style.borderColor = '#6366f1'; // indigo-500
            activeCard.style.borderWidth = '2px';
            
            // Background color depends on mode, but let's use a safe semi-transparent indigo
            // We can't easily detect mode in inline JS without checking class list of HTML, 
            // so we'll set a class that we define styles for, or just use a safe color.
            // Let's rely on the border for strong visibility, and a slight tint.
            activeCard.style.backgroundColor = 'rgba(99, 102, 241, 0.1)'; // indigo-500 with 10% opacity
        }

        // Toggle dynamic sections
        document.getElementById('settings-sleep').classList.add('hidden');
        document.getElementById('settings-checklist').classList.add('hidden');
        
        if (type === 'sleep') {
            document.getElementById('settings-sleep').classList.remove('hidden');
        } else if (type === 'checklist') {
            document.getElementById('settings-checklist').classList.remove('hidden');
        }
    }
    
    // Initialize standard selection
    toggleSettings('standard');
</script>

