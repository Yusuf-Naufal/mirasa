<x-layout.user.app title="Daftar Jenis Barang">

    <div class="space-y-2">
        <div class="flex flex-col gap-4 md:justify-between">

            <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center md:justify-between">
                <div class="flex gap-2">
                    <button type="button" onclick="openModal('addModal')"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2m5 11h-4v4h-2v-4H7v-2h4V7h2v4h4z" />
                        </svg>
                        <span class="hidden md:block md:ml-2">Tambah Jenis</span>
                    </button>

                </div>

                <form action="{{ route('barang.jenis.index') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#FFC829]"
                        placeholder="Cari...">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute left-3 top-5 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>
        </div>

        {{-- TABLE DAN CARD --}}
        <x-barang.jenis.table :jenis="$jenis" />
        <x-barang.jenis.card :jenis="$jenis" />

        @foreach ($jenis as $i)
            <x-barang.jenis.edit-modal :i="$i" />
        @endforeach

    </div>


    {{-- Modal Tambah --}}
    <div id="addModal"
        class="p-2 fixed inset-0 bg-black/50 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Tambah Jenis Barang</h2>
            <form action="{{ route('barang.jenis.store') }}" method="POST" class="form-prevent-multiple-submits">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Jenis</label>
                        <input type="text" name="nama_jenis" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode</label>
                        <input type="text" name="kode" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" onclick="closeModal('addModal')"
                        class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button
                        class="btn-submit flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-xl transition-all active:scale-95 shadow-sm disabled:opacity-70 disabled:cursor-not-allowed"
                        type="submit">
                        <span class="btn-text">Simpan</span>
                        <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>
        </div>
        </form>
    </div>
    </div>

</x-layout.user.app>
