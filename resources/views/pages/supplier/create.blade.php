<x-layout.user.app>
    <div class="py-2">

        <form action="{{ route('supplier.store') }}" method="POST"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M19.15 8a2 2 0 0 0-1.72-1H15V5a1 1 0 0 0-1-1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 1 1.73a3.49 3.49 0 0 0 7 .27h3.1a3.48 3.48 0 0 0 6.9 0a2 2 0 0 0 2-2v-3a1.1 1.1 0 0 0-.14-.52zM15 9h2.43l1.8 3H15zM6.5 19A1.5 1.5 0 1 1 8 17.5A1.5 1.5 0 0 1 6.5 19m10 0a1.5 1.5 0 1 1 1.5-1.5a1.5 1.5 0 0 1-1.5 1.5" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Data Suplier</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-1">
                            <label for="nama_supplier" class="block text-sm font-semibold text-gray-700">
                                Nama Supplier <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_supplier" name="nama_supplier" required
                                placeholder="Nama Supplier"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                        </div>

                        @if (auth()->user()->hasRole('Super Admin'))
                            <div class="space-y-1">
                                <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan
                                    <span class="text-red-500">*</span></label>
                                <select id="id_perusahaan" name="id_perusahaan" required
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="space-y-1">
                                <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan
                                    <span class="text-red-500">*</span></label>
                                <input type="text"
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border" disabled
                                    value="{{ auth()->user()->perusahaan->nama_perusahaan }}">
                                <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">
                            </div>
                        @endif

                        <div class="space-y-1 md:col-span-2 lg:col-span-1">
                            <label for="jenis_supplier" class="block text-sm font-semibold text-gray-700">
                                Jenis Supplier <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_supplier" name="jenis_supplier" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                <option value="Barang">Supplier Barang</option>
                                <option value="Singkong">Supplier Singkong</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="kode" class="block text-sm font-semibold text-gray-700">Kode Supplier <span
                                    class="text-red-500">*</span></label>
                            <div class="relative flex">
                                <span id="prefix-kode"
                                    class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 font-bold text-sm">
                                    SUP
                                </span>
                                <input type="text" id="kode" name="kode" required placeholder="XXX"
                                    class="w-full rounded-r-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border uppercase">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Kode final akan tersimpan otomatis dengan
                                format: SUP-KODE_SUPPLIER</p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('perusahaan.index') }}"
                        class="flex-1 sm:flex-none text-center border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 rounded-xl hover:text-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-xl transition-all active:scale-95 shadow-sm">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layout.user.app>
