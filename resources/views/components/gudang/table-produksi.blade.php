@props(['details'])

<div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden mx-4 md:mx-0"
    x-data="{
        // Modal Edit Lengkap
        editModalOpen: false,
        editData: { id: '', jumlah_diterima: '', harga: '', tgl_masuk: '', tgl_exp: '', no_batch: '', tempat: '' },
    
        // Modal Edit Cepat (Tambah/Kurangi Stok & Ubah Harga)
        showActionModal: false,
        modalTitle: '',
        actionType: '',
        activeId: '',
        activeHarga: 0,
        actionUrl: '{{ route('inventory.quick-update') }}',
    
        openEdit(item) {
            this.editData = item;
            this.editModalOpen = true;
        },
        openAddQty(id) {
            this.activeId = id;
            this.actionType = 'add';
            this.modalTitle = 'Tambah Stok Masuk';
            this.showActionModal = true;
        },
        openReduceQty(id) {
            this.activeId = id;
            this.actionType = 'reduce';
            this.modalTitle = 'Kurangi Stok (Penyesuaian)';
            this.showActionModal = true;
        },
        openEditPrice(data) {
            this.activeId = data.id;
            this.activeHarga = data.harga;
            this.actionType = 'price';
            this.modalTitle = 'Ubah Harga Satuan';
            this.showActionModal = true;
        }
    }">

    <div class="px-6 py-5 border-b border-slate-100 bg-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="font-bold text-slate-800">Riwayat Stok Masuk</h3>
        </div>

        <div>
            {{-- Tombol Menuju Halaman Create --}}
            <a href="{{ route('inventory.create-produksi') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-200 transition-all active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden md:block">Tambah Stok</span>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-400">
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-center w-16">
                        No</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">Tgl Masuk</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">Tgl Expired</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">No Batch</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">Tempat</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-right">Stok
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah Diterima
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-right">Harga
                        Satuan</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-center">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($details as $index => $i)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4 text-sm text-slate-400 text-center">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($i->tanggal_masuk)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium">
                                {{ \Carbon\Carbon::parse($i->tanggal_exp)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                            {{ $i->nomor_batch }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                            {{ $i->tempat_penyimpanan }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                            {{ number_format($i->stok, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span
                                class="text-sm font-black text-green-600 bg-green-50 px-3 py-1.5 rounded-xl ring-1 ring-green-100">
                                {{ number_format($i->jumlah_diterima, $i->jumlah_diterima == floor($i->jumlah_diterima) ? 0 : 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-slate-600">
                            Rp{{ number_format($i->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($i->stok == $i->jumlah_diterima - $i->jumlah_rusak)
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button"
                                        @click="openEdit({ 
                                    id: '{{ $i->id }}', 
                                    stok: '{{ $i->stok }}', 
                                    diterima: '{{ $i->jumlah_diterima }}', 
                                    harga: '{{ $i->harga }}', 
                                    tgl_masuk: '{{ $i->tanggal_masuk }}', 
                                    no_batch: '{{ $i->nomor_batch }}', 
                                    tempat: '{{ $i->tempat_penyimpanan }}', 
                                    tgl_exp: '{{ $i->tanggal_exp }}' 
                                })"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <form action="{{ route('inventory.destroy', $i->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini? Rekapitulasi akan disesuaikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-9 h-9 text-slate-400 hover:text-red-600 hover:bg-white rounded-xl shadow-sm border border-slate-200 hover:border-red-200 transition-all duration-200 bg-slate-50/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex items-center justify-center gap-1">

                                    <button type="button" @click="openAddQty('{{ $i->id }}')"
                                        class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg border border-emerald-100 transition-colors"
                                        title="Tambah Stok">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>

                                    <button type="button" @click="openReduceQty('{{ $i->id }}')"
                                        class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg border border-rose-100 transition-colors"
                                        title="Kurangi Stok (Manual)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg>
                                    </button>

                                    <button type="button"
                                        @click="openEditPrice({ id: '{{ $i->id }}', harga: '{{ $i->harga }}' })"
                                        class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg border border-amber-100 transition-colors"
                                        title="Ubah Harga">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>

                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-medium text-sm italic">Belum ada riwayat
                                    transaksi.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Update (Di luar loop, tapi masih dalam x-data) --}}
    <template x-teleport="body">
        <div x-show="editModalOpen"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-cloak>

            <div @click.away="editModalOpen = false"
                class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Edit Riwayat Stok</h3>
                    </div>
                    <button @click="editModalOpen = false"
                        class="p-2 hover:bg-white rounded-full text-slate-400 hover:text-slate-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="'/inventory-details/' + editData.id" method="POST" class="p-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-5 mb-8">
                        <div class="col-span-1">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Jumlah
                                Diterima</label>
                            <input type="number" name="jumlah_diterima" x-model="editData.diterima"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-slate-700 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Harga
                                Satuan (Rp)</label>
                            <input type="number" name="harga" x-model="editData.harga"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-slate-700 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tgl
                                Masuk</label>
                            <input type="date" name="tanggal_masuk" x-model="editData.tgl_masuk"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tgl
                                Expired</label>
                            <input type="date" name="tanggal_exp" x-model="editData.tgl_exp"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">No
                                Batch</label>
                            <input type="text" name="nomor_batch" x-model="editData.no_batch"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                        <div class="col-span-1">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tempat</label>
                            <input type="text" name="tempat_penyimpanan" x-model="editData.tempat"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="editModalOpen = false"
                            class="flex-1 px-4 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm">
                            Batalkan
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3.5 rounded-2xl bg-yellow-600 text-white font-bold hover:bg-yellow-700 shadow-lg shadow-yellow-200 transition-all text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="showActionModal" class="fixed inset-0 z-[1000] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showActionModal = false"></div>

                <div
                    class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 relative z-10">
                    <form :action="actionUrl" method="POST" class="form-prevent-multiple-submits p-8">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-2">
                                <template x-if="actionType === 'add'">
                                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg"><svg class="w-5 h-5"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg></div>
                                </template>
                                <template x-if="actionType === 'reduce'">
                                    <div class="p-2 bg-rose-100 text-rose-600 rounded-lg"><svg class="w-5 h-5"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg></div>
                                </template>
                                <template x-if="actionType === 'price'">
                                    <div class="p-2 bg-amber-100 text-amber-600 rounded-lg"><svg class="w-5 h-5"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg></div>
                                </template>
                                <h3 class="text-xl font-bold text-slate-800" x-text="modalTitle"></h3>
                            </div>
                        </div>

                        <input type="hidden" name="id" :value="activeId">
                        <input type="hidden" name="type" :value="actionType">

                        {{-- Info & Input untuk Qty (Add/Reduce) --}}
                        <template x-if="actionType === 'add' || actionType === 'reduce'">
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Catatan Sistem:</p>
                                    <p class="text-sm text-slate-600" x-show="actionType === 'add'">
                                        Aksi ini akan menambah Stok Aktif dan record Total Barang Diterima.
                                    </p>
                                    <p class="text-sm text-slate-600" x-show="actionType === 'reduce'">
                                        Digunakan untuk Penyesuaian Stok Fisik. Stok digital akan dikurangi tanpa
                                        mencatat transaksi keluar.
                                    </p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1"
                                        x-text="actionType === 'add' ? 'Jumlah Tambahan' : 'Jumlah Pengurangan'"></label>
                                    <input type="number" step="any" name="qty" required min="1"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                        </template>

                        {{-- Info & Input untuk Harga --}}
                        <template x-if="actionType === 'price'">
                            <div class="space-y-4">
                                <div
                                    class="flex justify-between items-center p-4 rounded-2xl bg-amber-50 border border-amber-100">
                                    <div>
                                        <p class="text-[10px] font-bold text-amber-700 uppercase">Harga Saat Ini</p>
                                        <p class="text-lg font-black text-amber-900">Rp <span
                                                x-text="new Intl.NumberFormat('id-ID').format(activeHarga)"></span></p>
                                    </div>
                                    <div class="text-right">
                                        <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Harga Satuan Baru
                                        (Rp)</label>
                                    <input type="number" step="any" name="harga" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-600"
                                        placeholder="Masukkan harga baru...">
                                </div>

                                <p class="text-[10px] text-rose-500 font-medium leading-relaxed italic">
                                    * Peringatan: Perubahan harga juga akan memperbarui nilai modal pada riwayat barang
                                    keluar yang terkait dengan batch stok ini.
                                </p>
                            </div>
                        </template>

                        <div class="flex gap-3 mt-8">
                            <button type="button" @click="showActionModal = false"
                                class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit"
                                class="btn-submit flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all disabled:opacity-70">

                                <span class="btn-text">Simpan Perubahan</span>

                                <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    // Gunakan event delegation agar bekerja pada elemen yang muncul dinamis (Alpine template)
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.form-prevent-multiple-submits');

        if (form) {
            // Cek validitas HTML5
            if (form.checkValidity()) {
                const btn = form.querySelector('.btn-submit');
                const btnText = form.querySelector('.btn-text');
                const btnSpinner = form.querySelector('.btn-spinner');

                if (btn) {
                    // Kunci tombol
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-not-allowed');

                    // Update UI
                    if (btnText) btnText.innerText = "Proses...";
                    if (btnSpinner) btnSpinner.classList.remove('hidden');
                }
            }
        }
    });
</script>
