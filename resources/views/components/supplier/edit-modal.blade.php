@props(['index', 'i'])

<div id="editModal-{{ $index }}"
    class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[9999] p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 text-left whitespace-normal relative">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Edit Data Supplier</h2>

        {{-- Route update menggunakan $index dari loop --}}
        <form action="{{ route('supplier.update', $index) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-6 text-left">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                    <input type="text" name="nama" required
                        {{-- Menggunakan array access [] karena data dari session --}}
                        value="{{ old('nama', $i['nama'] ?? '') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 uppercase">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                    <input type="text" name="kode" required
                        value="{{ old('kode', $i['kode'] ?? '') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 uppercase">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="kategori" required
                        value="{{ old('kategori', $i['kategori'] ?? '') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 uppercase">
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editModal-{{ $index }}')"
                    class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg">Batal</button>
                <button type="submit"
                    class="px-4 py-2 text-sm bg-[#0D1630] hover:bg-[#FFC829] hover:text-[#0D1630] text-white rounded-lg font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>