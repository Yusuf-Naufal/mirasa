<x-layout.beranda.app title="Daur Ulang / Konversi Afkir">
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- Header Section --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('barang-keluar.index', ['tab' => 'PENJUALAN']) }}"
                        class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-all mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Data Barang Keluar
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Konversi: <span class="text-rose-600">Daur Ulang Barang Keluar</span>
                    </h1>
                    <p class="text-gray-500 mt-1">Ubah barang yang sudah dikeluarkan/diafkir menjadi barang FG, WIP,
                        atau EC baru ke dalam gudang.</p>
                </div>
            </div>

            {{-- Notifikasi Error --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                    <p class="font-bold">Gagal!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Formulir Utama dengan Alpine.js --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden"
                x-data="{
                    stokTersedia: {{ $sisaBisaDikonversi }},
                    jumlahAfkirDikonversi: 0,
                    jumlahHasilKonversi: 0,
                    inputHarga: 0,
                
                    konversiAsal: {{ optional($afkirAsal->DetailInventory->Inventory->Barang)->nilai_konversi ?? 1 }},
                    selectedKonversi: 0,
                
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
                                'konversi' => $b->nilai_konversi ?? 1,
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
                        this.selectedKonversi = b.konversi;
                        this.selectedFoto = b.foto;
                        this.barangSearch = '';
                        this.barangOpen = false;
                    },
                
                    get totalNilai() {
                        let jml = parseFloat(this.jumlahHasilKonversi) || 0;
                        let hrg = parseFloat(this.inputHarga) || 0;
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(jml * hrg);
                    },
                
                    get totalKonversiAsal() {
                        let jml = parseFloat(this.jumlahAfkirDikonversi) || 0;
                        let konv = parseFloat(this.konversiAsal) || 0;
                        return new Intl.NumberFormat('id-ID').format(jml * konv) + ' KG';
                    },
                
                    get totalKonversiTujuan() {
                        if (!this.selectedBarangId) return '- KG';
                        let jml = parseFloat(this.jumlahHasilKonversi) || 0;
                        let konv = parseFloat(this.selectedKonversi) || 0;
                        return new Intl.NumberFormat('id-ID').format(jml * konv) + ' KG';
                    }
                }">

                <form action="{{ route('barang-keluar.afkir-ulang.store', $afkirAsal->id) }}" method="POST"
                    class="form-prevent-multiple-submits p-6 md:p-10">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                        {{-- ============================== --}}
                        {{-- KIRI: Sumber Barang Keluar (Yg Mau Didaur Ulang) --}}
                        {{-- ============================== --}}
                        <div class="lg:col-span-5 space-y-6">
                            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200">
                                <label class="block text-sm font-bold text-slate-500 mb-4 uppercase tracking-wider">
                                    Sumber Barang Ditolak / Afkir
                                </label>

                                <div class="flex items-center space-x-4 mb-4">
                                    <div
                                        class="w-16 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                        @if (optional($afkirAsal->DetailInventory->Inventory->Barang)->foto)
                                            <img src="{{ asset('storage/' . $afkirAsal->DetailInventory->Inventory->Barang->foto) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg leading-tight">
                                            {{ optional($afkirAsal->DetailInventory->Inventory->Barang)->nama_barang }}
                                        </h3>
                                        <p class="text-xs font-mono text-slate-500 mt-1">
                                            {{ optional($afkirAsal->DetailInventory->Inventory->Barang)->kode ?? 'NO-KODE' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Informasi Tambahan (Batch, Lokasi, Konversi) --}}
                                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-4 space-y-3">
                                    <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                                        <span
                                            class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                            Nomor Batch
                                        </span>
                                        <span
                                            class="text-[11px] font-black text-slate-700 bg-slate-100 px-2 py-0.5 rounded uppercase">
                                            {{ optional($afkirAsal->DetailInventory)->nomor_batch ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                                        <span
                                            class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                </path>
                                            </svg>
                                            Penyimpanan
                                        </span>
                                        <span class="text-xs font-bold text-slate-700">
                                            {{ optional($afkirAsal->DetailInventory)->tempat_penyimpanan ?? 'Belum diset' }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                                </path>
                                            </svg>
                                            Nilai Konversi
                                        </span>
                                        <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                            {{ optional($afkirAsal->DetailInventory->Inventory->Barang)->nilai_konversi ?? 1 }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total
                                            Qty Dikeluarkan</p>
                                        <p class="font-black text-rose-600 text-xl mt-1">
                                            {{ number_format($afkirAsal->jumlah_keluar, 0, ',', '.') }}
                                            <span
                                                class="text-sm font-medium text-slate-500">{{ optional($afkirAsal->DetailInventory->Inventory->Barang)->satuan }}</span>
                                        </p>
                                    </div>
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Harga
                                            / Satuan Lama</p>
                                        <p class="font-bold text-slate-700 text-sm mt-1">Rp
                                            {{ number_format($afkirAsal->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- Input JUMLAH YANG INGIN DIDAUR ULANG --}}
                                <div class="mt-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                                    <label class="text-xs font-bold text-rose-600 uppercase ml-1 flex justify-between">
                                        <span>Berapa yg ingin didaur ulang?</span>
                                        <span class="text-rose-500" x-show="jumlahAfkirDikonversi > stokTersedia">
                                            Maks: <span x-text="stokTersedia"></span>
                                        </span>
                                    </label>
                                    <div class="relative flex items-center mt-2">
                                        <input type="number" step="any" name="jumlah_afkir_dikonversi"
                                            placeholder="0" x-model.number="jumlahAfkirDikonversi" min="0.1"
                                            :max="stokTersedia" required
                                            class="w-full px-4 py-3 bg-white border rounded-xl outline-none font-black text-rose-600 text-lg shadow-sm"
                                            :class="jumlahAfkirDikonversi > stokTersedia ?
                                                'border-rose-400 focus:ring-rose-500 bg-rose-100' :
                                                'border-rose-200 focus:ring-2 focus:ring-rose-400'">
                                        <span
                                            class="absolute right-4 font-bold text-rose-400">{{ optional($afkirAsal->DetailInventory->Inventory->Barang)->satuan }}</span>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <span class="text-[10px] font-bold text-rose-400 tracking-wider">Berat Asal:
                                            <span class="text-rose-600" x-text="totalKonversiAsal"></span></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Section --}}
                            <div class="bg-gray-800 rounded-3xl p-6 text-white shadow-xl shadow-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">Nilai
                                        Barang Baru</span>
                                </div>
                                <div class="text-3xl font-black tracking-tight text-emerald-400" x-text="totalNilai">
                                    Rp 0
                                </div>
                                <p class="text-xs text-gray-400 mt-2">*Perhitungan = Jumlah Hasil Afkir × Harga Satuan
                                    Baru.</p>
                            </div>
                        </div>

                        {{-- ============================== --}}
                        {{-- KANAN: Form Barang Tujuan Baru --}}
                        {{-- ============================== --}}
                        <div class="lg:col-span-7 space-y-8">
                            <div
                                class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-6 border border-emerald-100/50">

                                <h3 class="text-lg font-bold text-emerald-900 mb-6 flex items-center">
                                    <span
                                        class="w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center mr-3 shadow-lg shadow-emerald-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    Didaur Ulang Menjadi (Masuk Gudang)
                                </h3>

                                <div class="space-y-6">
                                    {{-- Pilih Barang Tujuan --}}
                                    <div class="space-y-2 relative z-20">
                                        <label class="text-xs font-bold text-emerald-700 uppercase ml-1">Pilih Produk
                                            Hasil Daur Ulang</label>
                                        <div class="relative">
                                            <input type="hidden" name="id_barang_tujuan" :value="selectedBarangId"
                                                required>

                                            <button type="button" @click="barangOpen = !barangOpen"
                                                class="w-full px-5 py-4 bg-white border-0 rounded-2xl shadow-sm ring-1 ring-emerald-200 focus:ring-2 focus:ring-emerald-500 text-left flex justify-between items-center transition-all">
                                                <div class="flex items-center gap-3">
                                                    <template x-if="selectedFoto">
                                                        <img :src="selectedFoto"
                                                            class="w-6 h-6 rounded-md object-cover">
                                                    </template>
                                                    <span
                                                        :class="selectedBarangName ? 'text-gray-800 font-bold' : 'text-gray-400'"
                                                        x-text="selectedBarangName || '-- Cari & Pilih Barang Tujuan --'"></span>
                                                </div>
                                                <svg class="w-4 h-4 text-emerald-400 transition-transform"
                                                    :class="barangOpen ? 'rotate-180' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            {{-- Dropdown Menu --}}
                                            <div x-show="barangOpen" @click.away="barangOpen = false" x-cloak
                                                x-transition
                                                class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                                                <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                                    <input type="text" x-model="barangSearch"
                                                        placeholder="Ketik nama barang..."
                                                        class="w-full px-4 py-2 text-sm bg-white border border-gray-100 rounded-xl focus:ring-0 outline-none">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto custom-scrollbar text-sm">
                                                    <template x-for="b in filteredBarangs" :key="b.id">
                                                        <button type="button" @click="selectBarang(b)"
                                                            class="w-full px-5 py-3 text-left hover:bg-emerald-50 hover:text-emerald-600 transition-colors flex items-center justify-between gap-2 border-b border-gray-50 last:border-0">
                                                            <div class="flex flex-col gap-0.5">
                                                                <span
                                                                    class="font-bold text-gray-700 group-hover:text-emerald-600"
                                                                    x-text="b.name"></span>
                                                                <span class="text-[10px] text-gray-400 font-mono"
                                                                    x-text="b.kode"></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <span
                                                                    class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded block"
                                                                    x-text="b.satuan"></span>
                                                            </div>
                                                        </button>
                                                    </template>
                                                    <template x-if="filteredBarangs.length === 0">
                                                        <div class="p-4 text-center text-sm text-gray-400">Tidak ada
                                                            barang ditemukan.</div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Grid Input Group --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">

                                        {{-- Input HASIL AFKIR --}}
                                        <div class="md:col-span-2 space-y-1.5 mt-2">
                                            <label
                                                class="text-xs font-bold text-emerald-700 uppercase ml-1 flex justify-between">
                                                <span>Hasil Jadinya Berapa? (Masuk Rak)</span>
                                            </label>
                                            <div class="relative flex items-center">
                                                <input type="number" step="any" name="jumlah_hasil_konversi"
                                                    placeholder="0" x-model.number="jumlahHasilKonversi"
                                                    min="0.1" required :disabled="!selectedBarangId"
                                                    class="w-full px-4 py-4 bg-white border border-emerald-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all font-black text-gray-800 text-lg shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                <span class="absolute right-4 font-bold text-emerald-400"
                                                    x-text="selectedSatuan || 'Satuan'"></span>
                                            </div>
                                            <div class="mt-2 text-right">
                                                <span
                                                    class="text-[10px] font-bold text-emerald-500 tracking-wider">Berat
                                                    Hasil: <span class="text-emerald-700"
                                                        x-text="totalKonversiTujuan"></span></span>
                                            </div>
                                        </div>

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
                                                value="{{ $afkirAsal->tanggal_exp }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Harga
                                                Persatuan
                                                Baru</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                                <input type="number" step="any" name="harga" placeholder="0"
                                                    x-model.number="inputHarga" required
                                                    class="w-full pl-9 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none font-bold">
                                            </div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Lokasi
                                                Penyimpanan</label>
                                            <input type="text" name="tempat_penyimpanan"
                                                value="{{ $afkirAsal->tempat_penyimpanan }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>

                                        <div class="md:col-span-2 space-y-1.5">
                                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nomor Batch
                                                Baru</label>
                                            <input type="text" name="nomor_batch"
                                                placeholder="Kosongkan untuk generate otomatis"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Tombol Eksekusi --}}
                            <button type="submit"
                                :disabled="!selectedBarangId || jumlahAfkirDikonversi <= 0 || jumlahAfkirDikonversi >
                                    stokTersedia || jumlahHasilKonversi <= 0"
                                class="btn-submit w-full py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl transition-all flex items-center justify-center gap-3 mt-6"
                                :class="(!selectedBarangId || jumlahAfkirDikonversi <= 0 || jumlahAfkirDikonversi >
                                    stokTersedia || jumlahHasilKonversi <= 0) ?
                                'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none' :
                                'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-200 hover:scale-[1.02] active:scale-95'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="btn-text">Eksekusi Daur Ulang & Masukkan Gudang</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.beranda.app>
