<div id="editModal-{{ $i->id }}"
    class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[9999] p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 text-left whitespace-normal relative">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Edit Data Pemakaian</h2>

        <form action="{{ route('gas.update', $i->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-6 text-left">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="id_supplier" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        @foreach ($supplier as $s)
                            <option value="{{ $s->id }}" {{ $i->id_supplier == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemakaian</label>
                    <input type="date" name="tanggal_pemakaian" required
                        value="{{ old('tanggal_pemakaian', $i->tanggal_pemakaian) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Gas</label>
                    <input type="number" step="any" name="jumlah_gas" required
                        value="{{ old('jumlah_gas', $i->jumlah_gas) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 uppercase">
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editModal-{{ $i->id }}')"
                    class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg">Batal</button>
                <button type="submit"
                    class="px-4 py-2 text-sm bg-[#0D1630] hover:bg-[#FFC829] hover:text-[#0D1630] text-white rounded-lg font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>