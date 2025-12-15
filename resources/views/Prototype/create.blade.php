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
                    <label class="block font-medium">Nama Prototipe</label>
                    <input type="text" name="name"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Contoh: Tidur"
                        required>
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

