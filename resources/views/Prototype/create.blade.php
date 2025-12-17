<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            ➕ Tambah Prototipe Baru
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

            <form method="POST" action="{{ route('prototypes.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium mb-1">Kategori Aktivitas</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="cursor-pointer border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 peer-checked:border-blue-600 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                            <input type="radio" name="type" value="standard" class="hidden" checked onclick="toggleSettings('standard')">
                            <span class="text-2xl">📝</span>
                            <div class="text-xs font-bold mt-1">Biasa</div>
                        </label>
                        <label class="cursor-pointer border-2 border-gray-200 rounded-lg p-3 text-center hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="type" value="study" class="hidden" onclick="toggleSettings('study')">
                            <span class="text-2xl">📚</span>
                            <div class="text-xs font-bold mt-1">Belajar</div>
                        </label>
                        <label class="cursor-pointer border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50">
                            <input type="radio" name="type" value="sleep" class="hidden" onclick="toggleSettings('sleep')">
                            <span class="text-2xl">😴</span>
                            <div class="text-xs font-bold mt-1">Tidur</div>
                        </label>
                        <label class="cursor-pointer border-2 border-gray-200 rounded-lg p-3 text-center hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                            <input type="radio" name="type" value="checklist" class="hidden" onclick="toggleSettings('checklist')">
                            <span class="text-2xl">🥗</span>
                            <div class="text-xs font-bold mt-1">Rutinitas</div>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Nama Aktivitas</label>
                    <input type="text" name="name"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Contoh: Tidur Tepat Waktu"
                        required>
                </div>

                {{-- DYNAMIC SETTINGS --}}
                <div id="settings-sleep" class="mb-4 bg-purple-50 p-4 rounded border border-purple-200 hidden">
                    <label class="block font-medium text-purple-800 text-sm">Target Jam Tidur</label>
                    <input type="time" name="settings[target_time]" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                
                <div id="settings-checklist" class="mb-4 bg-green-50 p-4 rounded border border-green-200 hidden">
                    <label class="block font-medium text-green-800 text-sm">Target/Frekuensi (kali per hari)</label>
                    <input type="number" name="settings[target_count]" value="3" min="1" max="10" class="w-full border rounded px-3 py-2 mt-1">
                    <p class="text-xs text-green-600 mt-1">Misal: Makan 3x sehari</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Deskripsi (Opsional)</label>
                    <textarea name="description"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Target atau aturan prototipe"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Tanggal Mulai</label>
                    <input type="date" name="start_date"
                        class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Jam Pengingat Harian (Opsional)</label>
                    <input type="time" name="reminder_time"
                        class="w-full border rounded px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Notifikasi akan muncul di perangkat pada jam ini.</p>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 border rounded">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

<script>
    function toggleSettings(type) {
        document.getElementById('settings-sleep').classList.add('hidden');
        document.getElementById('settings-checklist').classList.add('hidden');
        
        if (type === 'sleep') {
            document.getElementById('settings-sleep').classList.remove('hidden');
        } else if (type === 'checklist') {
            document.getElementById('settings-checklist').classList.remove('hidden');
        }
    }
</script>

