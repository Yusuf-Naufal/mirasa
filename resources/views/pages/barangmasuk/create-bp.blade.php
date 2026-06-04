<x-layout.beranda.app title="Tambah Barang Masuk - Bahan Penolong">
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12" x-data="{
            jumlah: 0,
            jumlah_rusak: 0,
            jumlah_return: 0,
            ada_rusak: false,
            harga: 0,
            selectedFoto: '',
            selectedKode: '-',
            selectedSatuan: '-',
            
            // Watcher untuk reset nilai ketika checkbox uncheck
            init() {
                this.$watch('ada_rusak', (value) => {
                    if (!value) {
                        this.jumlah_rusak = 0;
                        this.jumlah_return = 0;
                    }
                });
            },

            get stok() {
                let j = parseFloat(this.jumlah) || 0;
                let rusakInternal = this.ada_rusak ? (parseFloat(this.jumlah_rusak) || 0) : 0;
                let rusakReturn = this.ada_rusak ? (parseFloat(this.jumlah_return) || 0) : 0;
                return Math.max(0, j - (rusakInternal + rusakReturn));
            },
            get total() { return this.stok * (parseFloat(this.harga) || 0) },
        
            get isInvalidStok() {
                let j = parseFloat(this.jumlah) || 0;
                // Perbaikan pemanggilan nama variabel
                let rusakInternal = this.ada_rusak ? (parseFloat(this.jumlah_rusak) || 0) : 0;
                let rusakReturn = this.ada_rusak ? (parseFloat(this.jumlah_return) || 0) : 0;
                return (rusakInternal + rusakReturn) > j;
            },
        
            updateBarang(e) {
                const opt = e.target.options[e.target.selectedIndex];
                this.selectedKode = opt.dataset.kode || '-';
                this.selectedSatuan = opt.dataset.satuan || '-';
                this.selectedFoto = opt.dataset.foto ? '/storage/' + opt.dataset.foto : '';
            }
        }">

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
                        Barang Masuk: <span class="text-yellow-600">Bahan Penolong</span>
                    </h1>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
                <form action="{{ route('barang-masuk.store-bahan') }}" method="POST"
                    class="form-prevent-multiple-submits p-6 md:p-10">
                    @csrf
                    <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                        {{-- Kiri: Pemilihan Barang & Supplier --}}
                        <div class="lg:col-span-5 space-y-6 relative z-30" x-data="{
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
                        
                            supplierOpen: false,
                            supplierSearch: '',
                            selectedSupplierId: '',
                            selectedSupplierName: '',
                            suppliers: {{ $supplier->map(fn($s) => ['id' => $s->id, 'name' => $s->nama_supplier])->toJson() }},
                        
                            get filteredBarangs() {
                                return this.barangs.filter(b => b.name.toLowerCase().includes(this.barangSearch.toLowerCase()))
                            },
                            get filteredSuppliers() {
                                return this.suppliers.filter(s => s.name.toLowerCase().includes(this.supplierSearch.toLowerCase()))
                            },
                        
                            selectBarang(b) {
                                this.selectedBarangId = b.id;
                                this.selectedBarangName = b.name;
                                this.selectedKode = b.kode;
                                this.selectedSatuan = b.satuan;
                                this.selectedFoto = b.foto;
                                this.barangSearch = '';
                                this.barangOpen = false;
                            },
                            selectSupplier(s) {
                                this.selectedSupplierId = s.id;
                                this.selectedSupplierName = s.name;
                                this.supplierSearch = '';
                                this.supplierOpen = false;
                            }
                        }">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-100/50">
                                <label class="block text-sm font-bold text-blue-900 mb-4 uppercase tracking-wider">
                                    Identitas Logistik
                                </label>

                                {{-- 1. Searchable Select: Supplier --}}
                                <div class="mb-4 space-y-2 relative z-[50]">
                                    <label class="text-[10px] font-bold text-blue-400 uppercase ml-1">Supplier /
                                        Vendor</label>
                                    <div class="relative">
                                        <input type="hidden" name="id_supplier" :value="selectedSupplierId">
                                        <button type="button" @click="supplierOpen = !supplierOpen; barangOpen = false"
                                            class="w-full px-5 py-3.5 bg-white border-0 rounded-2xl shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 text-left flex justify-between items-center transition-all">
                                            <span
                                                :class="selectedSupplierName ? 'text-gray-700 font-medium' : 'text-gray-400'"
                                                x-text="selectedSupplierName || '-- Cari & Pilih Supplier --'"></span>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform"
                                                :class="supplierOpen ? 'rotate-180' : ''" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div x-show="supplierOpen" @click.away="supplierOpen = false"
                                            class="absolute z-[100] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden"
                                            x-cloak x-transition>
                                            <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                                <input type="text" x-model="supplierSearch"
                                                    placeholder="Ketik nama supplier..."
                                                    class="w-full px-4 py-2 text-sm bg-white border border-gray-100 rounded-xl focus:ring-0 outline-none">
                                            </div>
                                            <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                                <template x-for="s in filteredSuppliers" :key="s.id">
                                                    <button type="button" @click="selectSupplier(s)"
                                                        class="w-full px-5 py-3 text-left text-sm hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center justify-between group">
                                                        <span x-text="s.name"></span>
                                                        <svg x-show="selectedSupplierId == s.id"
                                                            class="w-4 h-4 text-blue-500" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Image Preview --}}
                                <div class="relative group mb-4 text-center z-10">
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
                                            <img :src="selectedFoto" class="w-full h-full object-cover rounded-2xl">
                                        </template>
                                    </div>
                                </div>

                                {{-- 3. Searchable Select: Nama Barang --}}
                                <div class="space-y-4 relative z-[40]">
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
                                                class="absolute z-[100] w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
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
                                    <div class="grid grid-cols-2 gap-3 relative z-10">
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
                        <div class="lg:col-span-7 space-y-8 relative z-10">
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
                                    Informasi Kedatangan & QC
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Info Umum --}}
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal
                                            Masuk</label>
                                        <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Lokasi
                                            Penyimpanan</label>
                                        <input type="text" name="tempat_penyimpanan"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                            placeholder="Contoh: Rak A1">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-blue-600 uppercase ml-1">Jumlah
                                            Diterima</label>
                                        <input type="number" step="any" name="jumlah_diterima"
                                            x-model.number="jumlah"
                                            class="w-full px-4 py-3 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold outline-none">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Harga Per
                                            Satuan</label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                            <input type="number" step="any" name="harga"
                                                x-model.number="harga"
                                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold outline-none">
                                        </div>
                                    </div>

                                    {{-- Checkbox Barang Rusak --}}
                                    <div class="md:col-span-2 pt-2 pb-1">
                                        <label
                                            class="inline-flex items-center cursor-pointer p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" x-model="ada_rusak"
                                                class="form-checkbox h-5 w-5 text-red-500 rounded border-gray-300 focus:ring-red-500">
                                            <span class="ml-3 text-sm font-bold text-gray-700">Apakah ada barang yang
                                                rusak / bermasalah saat diterima?</span>
                                        </label>
                                    </div>

                                    {{-- Kondisional Form Rusak --}}
                                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5 p-5 bg-red-50/50 rounded-2xl border border-red-100 mb-2"
                                        x-show="ada_rusak" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0">

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-red-600 uppercase ml-1">Rusak
                                                Internal</label>
                                            <input type="number" step="any" name="jumlah_rusak"
                                                x-model.number="jumlah_rusak"
                                                class="w-full px-4 py-3 bg-white border border-red-200 rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-red-600 outline-none">
                                            <p class="text-[10px] text-red-500 font-medium ml-1">* Kesalahan sendiri
                                                (Dianggap rugi)</p>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-orange-600 uppercase ml-1">Rusak
                                                Supplier (Return)</label>
                                            <input type="number" step="any" name="jumlah_return"
                                                x-model.number="jumlah_return"
                                                class="w-full px-4 py-3 bg-white border border-orange-200 rounded-2xl focus:ring-2 focus:ring-orange-500 font-bold text-orange-600 outline-none">
                                            <p class="text-[10px] text-orange-500 font-medium ml-1">* Kesalahan
                                                supplier (Dapat di-return)</p>
                                        </div>
                                    </div>

                                    {{-- Stok Bersih --}}
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Stok Bersih
                                            (Masuk Gudang)</label>
                                        <input type="number" step="any" name="stok" :value="stok"
                                            readonly
                                            class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl font-black text-gray-800 shadow-inner focus:outline-none">
                                        <p class="text-[10px] text-gray-400 font-medium ml-1" x-show="ada_rusak">*
                                            Stok bersih = Diterima - (Rusak Internal + Return)</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Section --}}
                            <div class="bg-green-200 rounded-3xl p-6 shadow-xl">
                                <div
                                    class="flex justify-between items-center mb-1 text-green-700 text-xs font-bold uppercase">
                                    <span>Total Nilai Barang (Berdasarkan Stok Bersih)</span>
                                </div>
                                <div class="text-3xl font-black tracking-tight text-green-700 flex items-center gap-2">
                                    <span>Rp</span>
                                    <span x-text="new Intl.NumberFormat('id-ID').format(total)">0</span>
                                </div>
                                <input type="hidden" name="total_harga" :value="total">
                            </div>

                            {{-- Pesan Error Validasi --}}
                            <div x-show="isInvalidStok" x-cloak 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-xs font-bold text-red-600">
                                    Total barang rusak dan return tidak boleh melebihi jumlah barang yang diterima!
                                </p>
                            </div>

                            <button type="submit" :disabled="isInvalidStok"
                                :class="isInvalidStok ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:bg-green-700 hover:scale-[1.01] active:scale-95 shadow-lg shadow-green-200'"
                                class="btn-submit w-full bg-green-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3">

                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="btn-text">Simpan Barang</span>
                                    <svg class="btn-spinner hidden animate-spin h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.beranda.app>