<x-layout.beranda.app title="Afkir Ulang Barang">
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- Header Section --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('inventory.show', $detailAsal->id_inventory) }}"
                        class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-all mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Gudang
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Afkir Ulang: <span class="text-rose-600">Konversi Barang</span>
                    </h1>
                    <p class="text-gray-500 mt-1">Ubah stok barang saat ini menjadi jenis barang lain (FG, WIP, EC).</p>
                </div>
            </div>

            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                    <p class="font-bold">Gagal!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden"
                x-data="{
                    stokTersedia: {{ $detailAsal->stok }},
                    jumlahAfkir: 0,        
                    jumlahHasilAfkir: 0,   
                    inputHarga: 0,
                    
                    // --- DATA KONVERSI ---
                    konversiAsal: {{ $detailAsal->Inventory->Barang->nilai_konversi ?? 1 }},
                    selectedKonversi: 0, // Akan diisi saat barang tujuan dipilih
                
                    // Data Barang Tujuan
                    barangOpen: false,
                    barangSearch: '',
                    selectedBarangId: '',
                    selectedBarangName: '',
                    selectedKode: '',
                    selectedSatuan: '',
                    selectedFoto: '',
                    barangs: {{ $barangTujuan->map(
                            fn($b) => [
                                'id' => $b->id,
                                'name' => $b->nama_barang,
                                'kode' => $b->kode,
                                'satuan' => $b->satuan,
                                'konversi' => $b->nilai_konversi ?? 1, // Simpan nilai konversi tujuan ke Alpine
                                'foto' => $b->foto ? asset('storage/' . $b->foto) : '',
                            ],
                        )->toJson() }},
                
                    get filteredBarangs() {
                        return this.barangs.filter(b => b.name.toLowerCase().includes(this.barangSearch.toLowerCase()))
                    },
                
                    selectBarang(b) {
                        this.selectedBarangId = b.id;
                        this.selectedBarangName = b.name;
                        this.selectedKode = b.kode;
                        this.selectedSatuan = b.satuan;
                        this.selectedKonversi = b.konversi; // Update state konversi terpilih
                        this.selectedFoto = b.foto;
                        this.barangSearch = '';
                        this.barangOpen = false;
                    },
                
                    // Hitung Total Nilai Rupiah
                    get totalNilai() {
                        let jml = parseFloat(this.jumlahHasilAfkir) || 0;
                        let hrg = parseFloat(this.inputHarga) || 0;
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(jml * hrg);
                    },

                    // Hitung Total Konversi KG
                    get totalKonversiAsal() {
                        let jml = parseFloat(this.jumlahAfkir) || 0;
                        let konv = parseFloat(this.konversiAsal) || 0;
                        return new Intl.NumberFormat('id-ID').format(jml * konv) + ' KG';
                    },

                    get totalKonversiTujuan() {
                        if (!this.selectedBarangId) return '- KG';
                        let jml = parseFloat(this.jumlahHasilAfkir) || 0;
                        let konv = parseFloat(this.selectedKonversi) || 0;
                        return new Intl.NumberFormat('id-ID').format(jml * konv) + ' KG';
                    }
                }">

                <form action="{{ route('inventory.afkir-ulang.gudang', $detailAsal->id) }}" method="POST"
                    class="form-prevent-multiple-submits p-6 md:p-10">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                        {{-- KIRI: Informasi Barang Asal (Akan Dipotong Stoknya) --}}
                        <div class="lg:col-span-5 space-y-6">
                            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200">
                                <label class="block text-sm font-bold text-slate-500 mb-4 uppercase tracking-wider">
                                    Sumber Barang (Asal)
                                </label>

                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden">
                                        @if (optional($detailAsal->Inventory->Barang)->foto)
                                            <img src="{{ asset('storage/' . $detailAsal->Inventory->Barang->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg">
                                            {{ optional($detailAsal->Inventory->Barang)->nama_barang }}</h3>
                                        <p class="text-sm text-gray-500 font-mono">
                                            {{ optional($detailAsal->Inventory->Barang)->kode }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Stok Tersedia</p>
                                        <p class="font-black text-rose-600 text-xl mt-1">
                                            {{ number_format($detailAsal->stok, 0, ',', '.') }}
                                            <span class="text-sm font-medium text-slate-500">{{ optional($detailAsal->Inventory->Barang)->satuan }}</span>
                                        </p>
                                    </div>
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Konversi / Satuan</p>
                                        <p class="font-bold text-slate-700 text-xl mt-1 truncate">
                                            {{ $detailAsal->Inventory->Barang->nilai_konversi ?? '1' }} <span class="text-sm font-medium text-slate-500">KG</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tempat</p>
                                        <p class="font-bold text-slate-700 text-sm mt-1 truncate" title="{{ $detailAsal->tempat_penyimpanan }}">
                                            {{ $detailAsal->tempat_penyimpanan ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Nomor Batch</p>
                                        <p class="font-bold text-slate-700 text-sm mt-1 truncate" title="{{ $detailAsal->nomor_batch }}">
                                            {{ $detailAsal->nomor_batch ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Input POTONG STOK ASAL --}}
                                <div class="mt-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                                    <label class="text-xs font-bold text-rose-600 uppercase ml-1 flex justify-between">
                                        <span>Jumlah Afkir (Dipotong)</span>
                                        <span class="text-rose-500" x-show="jumlahAfkir > stokTersedia">
                                            Maks: <span x-text="stokTersedia"></span>
                                        </span>
                                    </label>
                                    <div class="relative flex items-center mt-2">
                                        <input type="number" step="any" name="jumlah_afkir" placeholder="0.00" x-model="jumlahAfkir" min="0.1" :max="stokTersedia" required
                                            class="w-full px-4 py-3 bg-white border rounded-xl outline-none transition-all font-black text-rose-600 text-lg shadow-sm"
                                            :class="jumlahAfkir > stokTersedia ? 'border-rose-400 focus:ring-rose-500 bg-rose-100' : 'border-rose-200 focus:ring-2 focus:ring-rose-400'">
                                        <span class="absolute right-4 font-bold text-rose-400">{{ optional($detailAsal->Inventory->Barang)->satuan }}</span>
                                    </div>
                                    {{-- INDIKATOR KONVERSI KG ASAL --}}
                                    <div class="mt-2 flex justify-between items-center px-1">
                                        <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Total Berat Asal:</span>
                                        <span class="text-sm font-black text-rose-600 bg-white px-2 py-0.5 rounded shadow-sm" x-text="totalKonversiAsal">0 KG</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Section --}}
                            <div class="bg-gray-800 rounded-3xl p-6 text-white shadow-xl shadow-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">Nilai Barang Baru</span>
                                </div>
                                <div class="text-3xl font-black tracking-tight text-emerald-400" x-text="totalNilai">
                                    Rp 0
                                </div>
                                <p class="text-xs text-gray-400 mt-2">*Perhitungan = Jumlah Hasil Afkir × Harga Satuan Baru.</p>
                            </div>
                        </div>

                        {{-- KANAN: Form Konversi Barang Tujuan --}}
                        <div class="lg:col-span-7 space-y-8">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-100/50">

                                <h3 class="text-lg font-bold text-blue-900 mb-6 flex items-center">
                                    <span class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center mr-3 shadow-lg shadow-blue-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    Barang Tujuan (Hasil Konversi)
                                </h3>

                                <div class="space-y-6">
                                    {{-- Searchable Select: Nama Barang Tujuan --}}
                                    <div class="space-y-2 relative z-20">
                                        <label class="text-xs font-bold text-blue-600 uppercase ml-1">Pilih Barang Tujuan</label>
                                        <div class="relative">
                                            <input type="hidden" name="id_barang_tujuan" :value="selectedBarangId" required>

                                            <button type="button" @click="barangOpen = !barangOpen"
                                                class="w-full px-5 py-4 bg-white border-0 rounded-2xl shadow-sm ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-500 text-left flex justify-between items-center transition-all">
                                                <div class="flex items-center gap-3">
                                                    <template x-if="selectedFoto">
                                                        <img :src="selectedFoto" class="w-6 h-6 rounded-md object-cover">
                                                    </template>
                                                    <span :class="selectedBarangName ? 'text-gray-800 font-bold' : 'text-gray-400'"
                                                        x-text="selectedBarangName || '-- Cari & Pilih Barang Tujuan --'"></span>
                                                </div>
                                                <svg class="w-4 h-4 text-blue-400 transition-transform" :class="barangOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            {{-- Dropdown Menu --}}
                                            <div x-show="barangOpen" @click.away="barangOpen = false" x-cloak x-transition
                                                class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                                                <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                                    <input type="text" x-model="barangSearch" placeholder="Ketik nama barang..." class="w-full px-4 py-2 text-sm bg-white border border-gray-100 rounded-xl focus:ring-0 outline-none">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto custom-scrollbar text-sm">
                                                    <template x-for="b in filteredBarangs" :key="b.id">
                                                        <button type="button" @click="selectBarang(b)" class="w-full px-5 py-3 text-left hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center justify-between gap-2">
                                                            <div class="flex flex-col gap-0.5">
                                                                <span class="font-bold text-gray-700 group-hover:text-blue-600" x-text="b.name"></span>
                                                                <span class="text-[10px] text-gray-400 font-mono" x-text="b.kode"></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded block" x-text="b.satuan"></span>
                                                                <span class="text-[9px] text-blue-400 mt-1 block font-bold" x-text="'Konv: ' + (b.konversi || 1) + ' KG'"></span>
                                                            </div>
                                                        </button>
                                                    </template>
                                                    <template x-if="filteredBarangs.length === 0">
                                                        <div class="p-4 text-center text-sm text-gray-400">Tidak ada barang ditemukan.</div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Grid Input Group --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">

                                        {{-- Input HASIL AFKIR (MASUK KE TUJUAN) --}}
                                        <div class="md:col-span-2 space-y-1.5 mt-2">
                                            <label class="text-xs font-bold text-blue-600 uppercase ml-1 flex justify-between">
                                                <span>Jumlah Hasil Afkir (Masuk Gudang)</span>
                                            </label>
                                            <div class="relative flex items-center">
                                                <input type="number" step="any" name="jumlah_hasil_afkir" placeholder="0.00" x-model="jumlahHasilAfkir" min="0.1" required :disabled="!selectedBarangId"
                                                    class="w-full px-4 py-4 bg-white border border-blue-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-black text-gray-800 text-lg shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                <span class="absolute right-4 font-bold text-blue-300" x-text="selectedSatuan || 'Satuan'"></span>
                                            </div>
                                            {{-- INDIKATOR KONVERSI KG TUJUAN --}}
                                            <div class="mt-2 flex justify-between items-center px-1">
                                                <span class="text-[10px] font-bold text-blue-400 uppercase tracking-wider">Total Berat Hasil:</span>
                                                <span class="text-sm font-black text-blue-600 bg-white px-2 py-0.5 rounded shadow-sm border border-blue-100" x-text="totalKonversiTujuan">- KG</span>
                                            </div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal Masuk</label>
                                            <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal Kadaluarsa</label>
                                            <input type="date" name="tanggal_exp" value="{{ $detailAsal->tanggal_exp }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Harga Per Satuan Baru</label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                                <input type="number" step="any" name="harga" id="inputHarga" placeholder="0" x-model="inputHarga" required
                                                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-800 placeholder-gray-300">
                                            </div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Lokasi Penyimpanan</label>
                                            <input type="text" name="tempat_penyimpanan" value="{{ $detailAsal->tempat_penyimpanan }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>

                                        <div class="md:col-span-2 space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nomor Batch Baru</label>
                                            <input type="text" name="nomor_batch" placeholder="Kosongkan untuk generate otomatis"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                :disabled="!selectedBarangId || jumlahAfkir <= 0 || jumlahAfkir > stokTersedia || jumlahHasilAfkir <= 0"
                                class="btn-submit w-full py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg transition-all flex items-center justify-center gap-3"
                                :class="(!selectedBarangId || jumlahAfkir <= 0 || jumlahAfkir > stokTersedia || jumlahHasilAfkir <= 0) ?
                                'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none' :
                                'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-200 hover:scale-[1.01] active:scale-95'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                <span class="btn-text">Proses Konversi (Afkir)</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.beranda.app>