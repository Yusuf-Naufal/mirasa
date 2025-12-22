<x-layout.user.app>
    {{-- Container w-full --}}
    <div class="w-full space-y-6 p-6" x-data="{ open: true }">
        
        {{-- Header & Breadcrumb --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Proses</h1>
                <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                    <a href="{{ route('proses.index') }}" class="hover:text-blue-600 transition-colors">Daftar Proses</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-medium">Tambah Baru</span>
                </div>
            </div>
            
            <a href="{{ route('proses.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 shadow-sm transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        {{-- Card Utama --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden transition-all">
            {{-- Header Form --}}
            <div 
                @click="open = !open" 
                class="p-6 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50/50 transition-colors"
            >
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Pilih Master Proses</h3>
                        <p class="text-xs text-gray-500 italic">Pilih jenis proses produksi yang ingin ditambahkan</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Body Form --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="p-8">
                {{-- Pastikan route action mengarah ke proses.store --}}
                <form method="POST" action="{{ route('proses.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Dropdown Pilihan Proses --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Jenis Proses</label>
                            <select name="template_proses" 
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3 px-4 text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white outline-none transition-all shadow-sm"
                                onchange="updateFields(this)">
                                <option value="" disabled selected>-- Pilih Kode & Nama Proses --</option>
                                <option value="PAC-XXMX|PACKING XX MX">PAC-XXMX - PACKING XX MX</option>
                                <option value="PAC-MAN|PACKING MANUAL">PAC-MAN - PACKING MANUAL</option>
                                <option value="PR-IFM|IFM">PR-IFM - IFM</option>
                                <option value="PR-MAN|MANUAL">PR-MAN - MANUAL</option>
                                <option value="TGJ-2|TRANSFER GUDANG JKT">TGJ-2 - TRANSFER GUDANG JKT</option>
                                <option value="TGB-3|TRANSFER GUDANG TIMUR">TGB-3 - TRANSFER GUDANG TIMUR</option>
                                <option value="PAC-EC|PACKING ECERAN">PAC-EC - PACKING ECERAN</option>
                            </select>
                        </div>

                        {{-- Input Hidden/Readonly untuk menangkap data --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Proses (Otomatis)</label>
                            <input type="text" id="display_kode" name="kode" readonly
                                class="w-full rounded-xl border border-gray-200 bg-gray-100 py-3 px-4 text-sm text-gray-500 outline-none transition-all shadow-sm" 
                                placeholder="Akan terisi otomatis...">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Proses (Otomatis)</label>
                            <input type="text" id="display_nama" name="proses" readonly
                                class="w-full rounded-xl border border-gray-200 bg-gray-100 py-3 px-4 text-sm text-gray-500 outline-none transition-all shadow-sm" 
                                placeholder="Akan terisi otomatis...">
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-6 mt-12 pt-6 border-t border-gray-100">
                        <a href="{{ route('proses.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 active:scale-95 transition-all duration-200 shadow-lg shadow-emerald-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan ke Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk Auto-Fill --}}
    <script>
        function updateFields(selectElement) {
            const value = selectElement.value;
            if (value) {
                const [kode, nama] = value.split('|');
                document.getElementById('display_kode').value = kode;
                document.getElementById('display_nama').value = nama;
            }
        }
    </script>
</x-layout.user.app>