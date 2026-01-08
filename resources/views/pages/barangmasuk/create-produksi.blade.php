<x-layout.beranda.app>
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- Header Section --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('barang-masuk.index') }}"
                        class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-all mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Barang Masuk: <span class="text-blue-600">Produksi</span>
                    </h1>
                    <p class="text-gray-500 mt-1">Kelola stok hasil produksi (FG, EC & WIP) ke dalam sistem inventori.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <form id="inventoryForm" action="{{ route('barang-masuk.store-produksi') }}" method="POST"
                    class="p-6 md:p-10">
                    @csrf
                    <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                        {{-- Kiri: Pemilihan Barang & Info --}}
                        <div class="lg:col-span-5 space-y-6" x-data="{
                            // Data Barang
                            barangOpen: false,
                            barangSearch: '',
                            selectedBarangId: '',
                            selectedBarangName: '',
                            barangs: {{ $barang->map(
                                    fn($b) => [
                                        'id' => $b->id,
                                        'name' => $b->nama_barang,
                                        'kode' => $b->kode,
                                        'satuan' => $b->satuan,
                                        'foto' => $b->foto ? asset('storage/' . $b->foto) : '',
                                    ],
                                )->toJson() }},
                        
                            // Filter Logic
                            get filteredBarangs() {
                                return this.barangs.filter(b => b.name.toLowerCase().includes(this.barangSearch.toLowerCase()))
                            },
                        
                            // Selection Logic
                            selectBarang(b) {
                                this.selectedBarangId = b.id;
                                this.selectedBarangName = b.name;
                                this.selectedKode = b.kode;
                                this.selectedSatuan = b.satuan;
                                this.selectedFoto = b.foto;
                                this.barangSearch = '';
                                this.barangOpen = false;
                            },
                        }">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-100/50">
                                <label
                                    class="block text-sm font-bold text-blue-900 mb-4 uppercase tracking-wider">Identitas
                                    Barang</label>

                                {{-- 2. Image Preview --}}
                                <div class="relative group mb-4 text-center">
                                    <div
                                        class="aspect-square w-full max-w-[150px] mx-auto bg-white rounded-2xl flex items-center justify-center border-2 border-dashed border-blue-200 overflow-hidden shadow-inner transition-all">
                                        <template x-if="!selectedFoto">
                                            <div class="text-center p-4">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-10 w-10 mx-auto text-blue-200 mb-2" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </template>
                                        <template x-if="selectedFoto">
                                            <img :src="selectedFoto"
                                                class="w-full h-full object-cover rounded-2xl animate-fade-in">
                                        </template>
                                    </div>
                                </div>

                                {{-- 3. Searchable Select: Nama Barang --}}
                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-blue-400 uppercase ml-1">Pilih Bahan
                                            Penolong</label>
                                        <div class="relative">
                                            <input type="hidden" name="id_barang" :value="selectedBarangId">
                                            <button type="button"
                                                @click="barangOpen = !barangOpen; supplierOpen = false"
                                                class="w-full px-5 py-3.5 bg-white border-0 rounded-2xl shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 text-left flex justify-between items-center transition-all">
                                                <span
                                                    :class="selectedBarangName ? 'text-gray-700 font-medium' : 'text-gray-400'"
                                                    x-text="selectedBarangName || '-- Cari & Pilih Nama Barang --'"></span>
                                                <svg class="w-4 h-4 text-gray-400 transition-transform"
                                                    :class="barangOpen ? 'rotate-180' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div x-show="barangOpen" @click.away="barangOpen = false"
                                                class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
                                                x-cloak x-transition>
                                                <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                                    <input type="text" x-model="barangSearch"
                                                        placeholder="Ketik nama barang..."
                                                        class="w-full px-4 py-2 text-sm bg-white border border-gray-100 rounded-xl focus:ring-0 outline-none">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto custom-scrollbar text-sm">
                                                    <template x-for="b in filteredBarangs" :key="b.id">
                                                        <button type="button" @click="selectBarang(b)"
                                                            class="w-full px-5 py-3 text-left hover:bg-blue-50 hover:text-blue-600 transition-colors flex flex-col gap-0.5">
                                                            <span
                                                                class="font-bold text-gray-700 group-hover:text-blue-600"
                                                                x-text="b.name"></span>
                                                            <span class="text-[10px] text-gray-400 font-mono"
                                                                x-text="b.kode"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 4. Info Kode & Satuan --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div
                                            class="bg-white/80 backdrop-blur-sm p-3 rounded-xl border border-blue-100 shadow-sm">
                                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-tighter">
                                                SKU / Kode</p>
                                            <p x-text="selectedKode || '-'"
                                                class="font-mono font-bold text-blue-900 mt-1 text-sm">-</p>
                                        </div>
                                        <div
                                            class="bg-white/80 backdrop-blur-sm p-3 rounded-xl border border-blue-100 shadow-sm">
                                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-tighter">
                                                Satuan</p>
                                            <p x-text="selectedSatuan || '-'"
                                                class="font-bold text-blue-900 mt-1 text-sm">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Detail Input --}}
                        <div class="lg:col-span-7 space-y-8">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                                    <span
                                        class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center mr-3 shadow-lg shadow-blue-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    Informasi Kedatangan Barang
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal
                                            Masuk</label>
                                        <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal
                                            Kadaluarsa</label>
                                        <input type="date" name="tanggal_exp"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-xs font-bold text-gray-500 uppercase ml-1 text-blue-600">Jumlah
                                            Diterima</label>
                                        <input type="number" step="any" name="jumlah_diterima" id="inputJumlah"
                                            class="w-full px-4 py-3 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-800 placeholder-gray-300"
                                            placeholder="0.00">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Harga Per
                                            Satuan</label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                            <input type="number" step="any" name="harga" id="inputHarga"
                                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-800 placeholder-gray-300"
                                                placeholder="0">
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Lokasi
                                            Penyimpanan</label>
                                        <input type="text" name="tempat_penyimpanan"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                            placeholder="Contoh: Rak A1, Gudang Dingin B, dsb.">
                                    </div>
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nomor Batch</label>
                                        <input type="text" name="nomor_batch"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                            placeholder="Contoh: 25 11 2025 A, A1073-A2096">
                                    </div>
                                </div>
                            </div>

                            {{-- Total Section --}}
                            <div class="bg-green-100 rounded-3xl p-6 text-white shadow-xl shadow-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-green-400 text-xs font-bold uppercase tracking-widest">Estimasi
                                        Total Nilai</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div id="displayTotal" class="text-3xl font-black tracking-tight text-green-600">
                                    Rp 0
                                </div>
                                <input type="hidden" name="total_harga" id="hiddenTotal" value="0">
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                <button type="submit"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-green-200 transition-all hover:scale-[1.02] active:scale-95">
                                    Konfirmasi & Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputJumlah = document.getElementById('inputJumlah');
            const inputHarga = document.getElementById('inputHarga');
            const displayTotal = document.getElementById('displayTotal');
            const hiddenTotal = document.getElementById('hiddenTotal');

            function hitungTotal() {
                const jumlah = parseFloat(inputJumlah.value) || 0;
                const harga = parseFloat(inputHarga.value) || 0;
                const total = jumlah * harga;
                displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                hiddenTotal.value = total;
            }

            inputJumlah.addEventListener('input', hitungTotal);
            inputHarga.addEventListener('input', hitungTotal);
        });
    </script>
</x-layout.beranda.app>
