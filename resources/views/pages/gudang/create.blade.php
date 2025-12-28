<x-layout.beranda.app>
    <div class="md:px-10 py-6 pt-24">
        <div class="max-w-3xl mx-auto">
            {{-- Breadcrumb & Header --}}
            <div class="mb-6">
                <a href="{{ route('inventory.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Gudang
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Barang Masuk: <span class="text-blue-600">{{ ucfirst(str_replace('-', ' ', $kategori)) }}</span></h1>
                <p class="text-gray-500 text-sm">Input data barang masuk untuk memperbarui stok gudang.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('inventory.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="kategori" value="{{ $kategori }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pilih Barang --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang</label>
                            <select name="id_barang" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="">-- Cari Barang --</option>
                                @foreach($barang as $b)
                                    <option value="{{ $b->id }}">{{ $b->kode }} - {{ $b->nama_barang }}</option>
                                @endforeach
                            </select>
                            @error('id_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Jumlah Masuk --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah (Qty)</label>
                            <input type="number" name="jumlah" min="1" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="0">
                            @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Catatan</label>
                        <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Pengiriman dari Supplier A..."></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4 border-t border-gray-50 flex justify-end gap-3">
                        <button type="reset" class="px-6 py-3 rounded-xl font-semibold text-gray-500 hover:bg-gray-100 transition-all">
                            Reset
                        </button>
                        <button type="submit" class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.beranda.app>