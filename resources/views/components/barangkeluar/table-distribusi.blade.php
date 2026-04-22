@props(['data'])

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{
    expandedGroup: null,
    editOpen: false,
    editData: { id: '', nama: '', jumlah: '', jenis: '', tanggal: '', maxStok: 0 },
    printModal: false,
    printData: {
        ids: [],
        no_jalan: '',
        no_faktur: '',
        template: 'biasa',
        jenis_kendaraan: '',
        plat_kendaraan: '',
        nama_supir: '',
        varietas: '',
        ppn: 11
    },

    returOpen: false,
    returData: {
        id: '',
        nama: '',
        qtyAsal: 0,
        total_retur: 0,
        afkir: 0,
        bagus: 0,

        // State Konversi Tambahan
        isKonversi: false,
        hasilKonversiQty: 0,
        hargaBaru: 0,
        batchBaru: '',

        // State Dropdown Barang Tujuan
        barangTujuanId: '',
        barangTujuanNama: '',
        barangTujuanSatuan: '',
        barangSearch: '',
        barangDropdownOpen: false
    },
    // Injeksi data dari Laravel ke JS
    listBarangTujuan: {{ isset($barangTujuan) ? $barangTujuan->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama_barang, 'satuan' => $b->satuan])->toJson() : '[]' }},

    get filteredBarangTujuan() {
        return this.listBarangTujuan.filter(b => b.nama.toLowerCase().includes(this.returData.barangSearch.toLowerCase()));
    },

    pilihBarangTujuan(b) {
        this.returData.barangTujuanId = b.id;
        this.returData.barangTujuanNama = b.nama;
        this.returData.barangTujuanSatuan = b.satuan;
        this.returData.barangDropdownOpen = false;
        this.returData.barangSearch = '';
    }
}">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="w-7 px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Penerima &
                        Entitas</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tipe
                        Distribusi</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Jenis
                        Barang</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total
                        Nilai Keluar</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $groupKey => $items)
                    @php
                        $firstItem = $items->first();
                        $isPenjualan = $firstItem->jenis_keluar === 'PENJUALAN';

                        $namaPenerima = $isPenjualan
                            ? $firstItem->Costumer->nama_costumer ?? 'Pelanggan Umum'
                            : $firstItem->Perusahaan->nama_perusahaan ?? 'Cabang Utama';

                        $jumlahJenisBarang = $items->pluck('DetailInventory.Inventory.id_barang')->unique()->count();
                        $totalGroupNilai = $items->sum(function ($item) {
                            $hargaSatuan =
                                $item->jumlah_keluar > 0 ? $item->total_harga / $item->jumlah_keluar : $item->harga;
                            return ($item->jumlah_keluar - ($item->jumlah_dikonversi ?? 0)) * $hargaSatuan;
                        });
                        $groupId = 'group-' . $loop->index;
                    @endphp

                    {{-- Baris Utama (Parent) --}}
                    <tr class="hover:bg-emerald-50/30 transition-colors cursor-pointer group"
                        @click="expandedGroup = (expandedGroup === '{{ $groupId }}' ? null : '{{ $groupId }}')">
                        <td class="px-6 py-5 text-center">
                            <div class="transition-transform duration-300"
                                :class="expandedGroup === '{{ $groupId }}' ? 'rotate-180' : ''">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm">{{ $namaPenerima }}</span>
                                <span class="text-[10px] text-emerald-600 font-black uppercase tracking-widest">
                                    {{ \Carbon\Carbon::parse($firstItem->tanggal_keluar)->format('d M Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <span
                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                    {{ $isPenjualan ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }}">
                                {{ $isPenjualan ? 'PENJUALAN' : 'TRANSFER' }}
                            </span>
                        </td>
                        <td class="px-4 py-5 text-right font-black text-gray-700 text-sm">
                            {{ number_format($jumlahJenisBarang, 0) }}
                        </td>
                        <td class="px-4 py-5 text-right font-black text-emerald-600 text-sm">
                            Rp {{ number_format($totalGroupNilai, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                @php
                                    // Cek apakah ada baris dalam grup ini yang sudah memiliki data cetak
                                    // Kita anggap jika salah satu sudah diisi, maka grup ini statusnya "Sudah Dicetak/Diproses"
                                    $isProcessed = $items->every(
                                        fn($item) => !empty($item->no_faktur) && !empty($item->no_jalan),
                                    );

                                    // Ambil data keterangan dari item pertama jika ada (asumsi data grup seragam)
                                    $existingKeterangan = json_decode($firstItem->keterangan, true) ?? [];
                                @endphp

                                @can('barang-keluar.edit')
                                    @if (!$isProcessed)
                                        {{-- TAMPILKAN TOMBOL PRINT (Jika data masih kosong/null) --}}
                                        <button
                                            @click.stop="
                                                printData.ids = [{{ $items->pluck('id')->implode(',') }}];
                                                printData.no_jalan = '{{ $firstItem->no_jalan }}';
                                                printData.no_faktur = '{{ $firstItem->no_faktur }}';
                                                printData.penerima = '{{ $namaPenerima }}';
                                                printData.asal = '{{ auth()->user()->perusahaan->nama_perusahaan ?? 'PT. Mirasa Food Industri' }}';
                                                printData.ringkasanBarang = [
                                                    @foreach ($items as $item)
                                                        @php
                                                            $qtyAsli = $item->jumlah_keluar;
                                                            $qtyAfkir = $item->jumlah_dikonversi ?? 0;
                                                            $qtyNetto = $qtyAsli - $qtyAfkir;
                                                            $totalNetto = $qtyNetto * $item->harga;
                                                        @endphp
                                                        {
                                                            nama: '{{ $item->DetailInventory->Inventory->Barang->nama_barang }}',
                                                            qty_asli: '{{ number_format($qtyAsli, 0) }}',
                                                            qty_afkir: '{{ number_format($qtyAfkir, 0) }}',
                                                            qty_netto: '{{ number_format($qtyNetto, 0) }}',
                                                            satuan: '{{ $item->DetailInventory->Inventory->Barang->satuan }}',
                                                            batch: '{{ $item->DetailInventory->nomor_batch ?? '-' }}',
                                                            harga: '{{ number_format($item->harga, 0, ',', '.') }}',
                                                            total: '{{ number_format($totalNetto, 0, ',', '.') }}',
                                                            is_afkir: {{ $qtyAfkir > 0 ? 'true' : 'false' }}
                                                        }, @endforeach
                                                ];
                                                printModal = true;
                                            "
                                            class="flex items-center gap-2 px-3 py-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </button>
                                    @else
                                        {{-- TAMPILKAN TOMBOL LIHAT & EDIT (Jika data sudah terisi) --}}
                                        <button
                                            @click.stop="
                                            printData.ids = [{{ $items->pluck('id')->implode(',') }}];
                                            printData.no_jalan = '{{ $firstItem->no_jalan }}';
                                            printData.no_faktur = '{{ $firstItem->no_faktur }}';
                                            printData.template = '{{ $existingKeterangan['jenis_template'] ?? 'biasa' }}';
                                            printData.jenis_kendaraan = '{{ $existingKeterangan['jenis_kendaraan'] ?? '' }}';
                                            printData.plat_kendaraan = '{{ $existingKeterangan['plat_kendaraan'] ?? '' }}';
                                            printData.nama_supir = '{{ $existingKeterangan['nama_supir'] ?? '' }}';
                                            printData.varietas = '{{ $existingKeterangan['varietas'] ?? '' }}';
                                            printData.ppn = '{{ $existingKeterangan['ppnPercent'] ?? 11 }}';
                                            
                                            printData.penerima = '{{ $namaPenerima }}';
                                            printData.asal = '{{ auth()->user()->perusahaan->nama_perusahaan ?? 'PT. Mirasa Food Industri' }}';
                                            printData.ringkasanBarang = [
                                                @foreach ($items as $item)
                                                    @php
                                                        $qtyAsli = $item->jumlah_keluar;
                                                        $qtyAfkir = $item->jumlah_dikonversi ?? 0;
                                                        $qtyNetto = $qtyAsli - $qtyAfkir;
                                                        $totalNetto = $qtyNetto * $item->harga;
                                                    @endphp
                                                    {
                                                        nama: '{{ $item->DetailInventory->Inventory->Barang->nama_barang }}',
                                                        qty_asli: '{{ number_format($qtyAsli, 0) }}',
                                                        qty_afkir: '{{ number_format($qtyAfkir, 0) }}',
                                                        qty_netto: '{{ number_format($qtyNetto, 0) }}',
                                                        satuan: '{{ $item->DetailInventory->Inventory->Barang->satuan }}',
                                                        batch: '{{ $item->DetailInventory->nomor_batch ?? '-' }}',
                                                        harga: '{{ number_format($item->harga, 0, ',', '.') }}',
                                                        total: '{{ number_format($totalNetto, 0, ',', '.') }}',
                                                        is_afkir: {{ $qtyAfkir > 0 ? 'true' : 'false' }}
                                                    }, @endforeach
                                            ];
                                            printModal = true;
                                        "
                                            class="flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    {{-- Baris Detail (Expanded) --}}
                    <tr x-show="expandedGroup === '{{ $groupId }}'" x-collapse x-cloak class="bg-gray-50/30">
                        <td colspan="7" class="p-0">
                            <div class="px-16 py-6 border-l-4 border-emerald-500 ml-8 my-2">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                            <th class="py-2 text-left">Produk</th>
                                            <th class="py-2 text-center">Batch</th>
                                            <th class="py-2 text-right">Qty</th>
                                            <th class="py-2 text-right">Harga</th>
                                            <th class="py-2 text-right">Subtotal</th>
                                            <th class="py-2 text-right w-24">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($items as $item)
                                            @php $barang = $item->DetailInventory->Inventory->Barang; @endphp
                                            <tr class="group/item">
                                                <td class="py-3">
                                                    <span
                                                        class="text-xs font-bold text-gray-700 leading-tight block">{{ $barang->nama_barang }}</span>
                                                </td>
                                                <td class="py-3 text-center font-mono text-[10px] text-gray-400">
                                                    {{ $item->DetailInventory->nomor_batch ?? '-' }}
                                                </td>
                                                <td class="py-3 text-right">
                                                    @php
                                                        $qAsli = $item->jumlah_keluar;
                                                        $qAfkir = $item->jumlah_dikonversi ?? 0;
                                                        $qNetto = $qAsli - $qAfkir;
                                                    @endphp

                                                    @if ($qAfkir > 0)
                                                        <div class="flex flex-col items-end">
                                                            {{-- Kuantitas Netto (Sisa Bersih) --}}
                                                            <span class="font-black text-emerald-600 text-xs"
                                                                title="Kuantitas Bersih (Netto)">
                                                                {{ number_format($qNetto, 0) }} {{ $barang->satuan }}
                                                            </span>
                                                            {{-- Keterangan Coretan Asli & Afkir --}}
                                                            <span
                                                                class="text-[9px] font-bold text-gray-400 mt-0.5 flex gap-1">
                                                                <span class="line-through"
                                                                    title="Kuantitas Awal yang dikeluarkan">
                                                                    {{ number_format($qAsli, 0) }}
                                                                </span>
                                                                <span class="text-amber-500 bg-amber-50 px-1 rounded"
                                                                    title="Didaur Ulang / Afkir">
                                                                    -{{ number_format($qAfkir, 0) }}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="font-bold text-xs text-gray-800">
                                                            {{ number_format($qAsli, 0) }} {{ $barang->satuan }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 text-right text-xs text-gray-500">
                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                </td>
                                                <td class="py-3 text-right">
                                                    @php
                                                        $hargaSatuan =
                                                            $item->jumlah_keluar > 0
                                                                ? $item->total_harga / $item->jumlah_keluar
                                                                : $item->harga;
                                                        $tAsli = $item->total_harga;
                                                        $tAfkir = ($item->jumlah_dikonversi ?? 0) * $hargaSatuan;
                                                        $tNetto = $tAsli - $tAfkir;
                                                    @endphp

                                                    @if (($item->jumlah_dikonversi ?? 0) > 0)
                                                        <div class="flex flex-col items-end">
                                                            {{-- Subtotal Netto (Nilai Bersih) --}}
                                                            <span class="text-xs font-black text-emerald-600"
                                                                title="Total Nilai Bersih">
                                                                Rp {{ number_format($tNetto, 0, ',', '.') }}
                                                            </span>
                                                            {{-- Keterangan Nilai Kotor Dicoret --}}
                                                            <span
                                                                class="text-[9px] font-bold text-gray-400 mt-0.5 flex gap-1">
                                                                <span class="line-through" title="Total Nilai Awal">
                                                                    Rp {{ number_format($tAsli, 0, ',', '.') }}
                                                                </span>
                                                                <span class="text-amber-500 bg-amber-50 px-1 rounded"
                                                                    title="Potongan Retur/Afkir">
                                                                    -Rp {{ number_format($tAfkir, 0, ',', '.') }}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-xs font-black text-gray-800">
                                                            Rp {{ number_format($tAsli, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 text-right">
                                                    <div class="flex justify-end" x-data="{ actionOpen: false }">

                                                        {{-- Tombol Toggle Dropdown (Titik Tiga) --}}
                                                        <button type="button" x-ref="btnDropdown"
                                                            @click="actionOpen = !actionOpen"
                                                            class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                                            <svg class="w-5 h-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                            </svg>
                                                        </button>

                                                        {{-- Menu Dropdown (Di-teleport ke Body agar keluar dari tabel) --}}
                                                        <template x-teleport="body">
                                                            <div x-show="actionOpen" @click.away="actionOpen = false"
                                                                x-cloak x-transition.opacity.duration.200ms
                                                                x-init="$watch('actionOpen', val => {
                                                                    if (val) {
                                                                        const rect = $refs.btnDropdown.getBoundingClientRect();
                                                                        // Kalkulasi posisi agar pas di bawah tombol
                                                                        $el.style.top = (rect.bottom + window.scrollY + 4) + 'px';
                                                                        $el.style.left = (rect.right - 176 + window.scrollX) + 'px'; // 176px adalah lebar w-44
                                                                    }
                                                                })"
                                                                @scroll.window="actionOpen = false"
                                                                @resize.window="actionOpen = false"
                                                                class="absolute w-44 bg-white rounded-xl shadow-2xl border border-gray-100 py-1.5 z-[150] overflow-hidden">

                                                                {{-- Edit Button --}}
                                                                @can('barang-keluar.edit')
                                                                    <button type="button"
                                                                        @click="
                                                                            editOpen = true; 
                                                                            actionOpen = false; 
                                                                            editData = {
                                                                                id: '{{ $item->id }}', 
                                                                                nama: '{{ $barang->nama_barang }}',
                                                                                jumlah: '{{ $item->jumlah_keluar }}',
                                                                                jenis: '{{ $item->jenis_keluar }}',
                                                                                tanggal: '{{ $item->tanggal_keluar }}',
                                                                                maxStok: {{ $item->DetailInventory->stok + $item->jumlah_keluar }},
                                                                                // TAMBAHKAN BARIS INI: Batas minimum karena sudah direcycle
                                                                                minBolehEdit: {{ $item->jumlah_dikonversi ?? 0 }} 
                                                                            }
                                                                        "
                                                                        class="w-full px-4 py-2.5 text-left text-xs font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-600 transition-colors flex items-center gap-2.5">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                        Edit Data
                                                                    </button>
                                                                @endcan

                                                                {{-- AFkir Button --}}
                                                                @can('barang-keluar.afkir-ulang')
                                                                    <a type="button"
                                                                        href="{{ route('barang-keluar.afkir-ulang', $item->id) }}"
                                                                        class="w-full px-4 py-2.5 text-left text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors flex items-center gap-2.5 border-t border-gray-50">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                                        </svg>
                                                                        Afkir Ulang
                                                                    </a>
                                                                @endcan

                                                                {{-- Delete Button --}}
                                                                @if ($item->jumlah_dikonversi == 0)
                                                                    @can('barang-keluar.delete')
                                                                        <form
                                                                            action="{{ route('barang-keluar.destroy', $item->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Hapus data ini? Stok akan dikembalikan ke inventaris.')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="w-full px-4 py-2.5 text-left text-xs font-bold text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center gap-2.5 border-t border-gray-50">
                                                                                <svg class="w-4 h-4" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                                Hapus Data
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                @endif

                                                            </div>
                                                        </template>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Print Group --}}
                    <template x-teleport="body">
                        <div x-show="printModal" class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
                            <div class="flex items-center justify-center min-h-screen px-4">
                                {{-- Backdrop (Animasi Dihapus) --}}
                                <div x-show="printModal" @click="printModal = false"
                                    class="fixed inset-0 bg-gray-600/75 backdrop-blur-sm"></div>

                                {{-- Card Modal (Animasi Dihapus) --}}
                                <div x-show="printModal"
                                    class="relative inline-block w-full max-w-4xl overflow-hidden text-left align-middle bg-white shadow-2xl rounded-[2.5rem]">

                                    <div class="flex flex-col md:flex-row">
                                        {{-- SISI KIRI: FORM INPUT --}}
                                        <div class="w-full md:w-3/5 p-8 border-r border-gray-100">
                                            <div class="mb-6">
                                                <h3 class="text-2xl font-black text-gray-900 leading-tight">Lengkapi
                                                    Data Cetak</h3>
                                                <p
                                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                                                    Silahkan isi informasi dokumen & kendaraan</p>
                                            </div>

                                            <form action="{{ route('barang-keluar.print-group') }}" method="GET"
                                                target="_blank" @submit="printModal = false">
                                                <template x-for="id in printData.ids" :key="id">
                                                    <input type="hidden" name="ids[]" :value="id">
                                                </template>

                                                <div class="space-y-5">
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="template" value="biasa"
                                                                x-model="printData.template" class="hidden peer">
                                                            <div
                                                                class="p-3 text-center border-2 rounded-2xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-xs font-black uppercase tracking-widest text-gray-600">
                                                                SJ</div>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="template" value="indofood"
                                                                x-model="printData.template" class="hidden peer">
                                                            <div
                                                                class="p-3 text-center border-2 rounded-2xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-xs font-black uppercase tracking-widest text-gray-600">
                                                                SJ & Invoice</div>
                                                        </label>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div class="space-y-1">
                                                            <label
                                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">No.
                                                                Faktur</label>
                                                            <input type="text" name="no_faktur"
                                                                x-model="printData.no_faktur"
                                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm text-gray-700">
                                                        </div>
                                                        <div class="space-y-1">
                                                            <label
                                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">No.
                                                                Jalan / PO</label>
                                                            <input type="text" name="no_jalan"
                                                                x-model="printData.no_jalan"
                                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm text-gray-700">
                                                        </div>
                                                    </div>

                                                    <div class="space-y-3 pt-2">
                                                        <label
                                                            class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Keterangan
                                                            Kendaraan & Supir</label>
                                                        <input type="text" name="jenis_kendaraan"
                                                            x-model="printData.jenis_kendaraan"
                                                            placeholder="Jenis Kendaraan"
                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold text-gray-700">
                                                        <input type="text" name="plat_kendaraan"
                                                            x-model="printData.plat_kendaraan"
                                                            placeholder="Nomor Polisi"
                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold text-gray-700">
                                                        <input type="text" name="nama_supir"
                                                            x-model="printData.nama_supir" placeholder="Nama Supir"
                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold text-gray-700">
                                                    </div>

                                                    {{-- Varietas (Tanpa Animasi x-transition) --}}
                                                    <div x-show="printData.template === 'biasa'" class="space-y-1.5">
                                                        <label
                                                            class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Varietas
                                                            Produk</label>
                                                        <input type="text" name="varietas"
                                                            x-model="printData.varietas" placeholder="Contoh: MGU/B"
                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold text-gray-700">
                                                    </div>

                                                    {{-- PPN (Tanpa Animasi x-transition) --}}
                                                    <div x-show="printData.template === 'indofood'"
                                                        class="space-y-1.5 p-4 bg-blue-50 border border-blue-100 rounded-2xl">
                                                        <label
                                                            class="block text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">PPN
                                                            (%)
                                                        </label>
                                                        <input type="number" name="ppn" x-model="printData.ppn"
                                                            class="w-full px-4 py-3 bg-white border border-blue-200 rounded-xl outline-none font-bold text-sm text-gray-700">
                                                    </div>
                                                </div>

                                                <div class="mt-8 flex gap-3">
                                                    <button type="button" @click="printModal = false"
                                                        class="px-6 py-4 text-xs font-black uppercase text-gray-400 hover:text-gray-600">Batal</button>
                                                    <button type="submit"
                                                        class="flex-1 px-8 py-4 text-xs font-black uppercase tracking-widest text-white bg-emerald-600 rounded-2xl shadow-xl shadow-emerald-200 active:scale-95">Buka
                                                        Print Preview</button>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- SISI KANAN: RINGKASAN PRODUK --}}
                                        <div class="w-full md:w-2/5 bg-gray-50/50 p-8">
                                            <div class="mb-6">
                                                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">
                                                    Detail Distribusi</h4>
                                                <div class="h-1 w-12 bg-emerald-500 mt-1 rounded-full"></div>
                                            </div>

                                            <div class="space-y-6">
                                                <div class="space-y-4">
                                                    <div class="flex items-start gap-3">
                                                        <div class="p-2 bg-white rounded-lg border border-gray-100">
                                                            <svg class="w-4 h-4 text-emerald-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Asal
                                                                Pengiriman</span>
                                                            <p class="text-xs font-black text-gray-800"
                                                                x-text="printData.asal"></p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start gap-3">
                                                        <div class="p-2 bg-white rounded-lg border border-gray-100">
                                                            <svg class="w-4 h-4 text-blue-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Tujuan
                                                                Penerima</span>
                                                            <p class="text-xs font-black text-gray-800"
                                                                x-text="printData.penerima"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="border-gray-200">

                                                <div>
                                                    <span
                                                        class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Item
                                                        dalam pengiriman</span>
                                                    <div class="max-h-[350px] overflow-y-auto pr-2 space-y-3">
                                                        <template x-for="(item, index) in printData.ringkasanBarang"
                                                            :key="index">
                                                            <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-sm transition-none"
                                                                :class="item.is_afkir ? 'border-amber-200 bg-amber-50/30' : ''">

                                                                <div class="flex justify-between items-start mb-1">
                                                                    <div class="flex flex-col">
                                                                        <span
                                                                            class="text-[10px] font-black text-gray-800 uppercase"
                                                                            x-text="item.nama"></span>
                                                                        <span
                                                                            class="text-[9px] text-gray-500 font-medium mt-0.5"
                                                                            x-text="'Batch: ' + item.batch"></span>

                                                                        {{-- Label Warning Jika Ada Afkir --}}
                                                                        <template x-if="item.is_afkir">
                                                                            <span
                                                                                class="text-[8px] font-bold text-amber-600 mt-1 bg-amber-100 px-1.5 py-0.5 rounded w-max">
                                                                                ⚠️ Terdapat Afkir/Retur: <span
                                                                                    x-text="item.qty_afkir"></span>
                                                                                <span x-text="item.satuan"></span>
                                                                            </span>
                                                                        </template>
                                                                    </div>

                                                                    <div class="text-right">
                                                                        {{-- Coret Qty Asli jika ada afkir --}}
                                                                        <template x-if="item.is_afkir">
                                                                            <span
                                                                                class="text-[8px] font-bold text-gray-400 line-through block"
                                                                                x-text="item.qty_asli + ' ' + item.satuan"></span>
                                                                        </template>
                                                                        {{-- Qty Final/Netto --}}
                                                                        <span
                                                                            class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded"
                                                                            :class="item.qty_netto == 0 ?
                                                                                'bg-red-50 text-red-600' : ''"
                                                                            x-text="item.qty_netto + ' ' + item.satuan"></span>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="flex justify-between items-center text-[9px] font-bold text-gray-400 mt-1.5 pt-1.5 border-t border-gray-50">
                                                                    <span>Harga: Rp <span
                                                                            x-text="item.harga"></span></span>
                                                                    <span
                                                                        :class="item.is_afkir ? 'text-amber-700' :
                                                                            'text-gray-700'">
                                                                        Total Bersih: Rp <span
                                                                            x-text="item.total"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400 italic">Data distribusi tidak
                            ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Teleport --}}
    <template x-teleport="body">
        <div x-show="editOpen" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                {{-- Backdrop --}}
                <div x-show="editOpen" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    @click="editOpen = false"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75 backdrop-blur-sm transition-opacity">
                </div>

                {{-- Card Modal --}}
                <div x-show="editOpen" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative inline-block w-full max-w-lg p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem]">

                    <form :action="`{{ url('barang-keluar') }}/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-2xl font-black text-gray-900 leading-tight">Edit Distribusi</h3>
                            <p class="text-sm font-bold text-emerald-600 uppercase tracking-widest mt-1"
                                x-text="editData.nama"></p>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Tanggal Keluar --}}
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal
                                        Keluar</label>
                                    <input type="date" name="tanggal_keluar" x-model="editData.tanggal" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none font-bold text-sm transition-all text-gray-700">
                                </div>

                                {{-- Jumlah Keluar --}}
                                <div class="space-y-1.5">
                                    <div class="flex justify-between items-center px-1">
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah</label>
                                        <span class="text-[10px] font-bold uppercase"
                                            :class="parseFloat(editData.jumlah) > parseFloat(editData.maxStok) ?
                                                'text-red-500' : 'text-emerald-500'">
                                            Max: <span x-text="parseFloat(editData.maxStok).toFixed(2)"></span>
                                        </span>
                                    </div>

                                    {{-- Tambahkan :min="editData.minBolehEdit" --}}
                                    <input type="number" name="jumlah_keluar" x-model="editData.jumlah"
                                        :max="editData.maxStok" :min="editData.minBolehEdit" step="any" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 outline-none font-black text-lg transition-all"
                                        :class="(parseFloat(editData.jumlah) > parseFloat(editData.maxStok) || parseFloat(
                                            editData.jumlah) < parseFloat(editData.minBolehEdit)) ?
                                        'focus:ring-red-500 border-red-200 bg-red-50 text-red-600' :
                                        'focus:ring-emerald-500 text-gray-700'">

                                    {{-- Pesan Peringatan Jika Angka Di Bawah Batas Minimum --}}
                                    <p x-show="parseFloat(editData.jumlah) < parseFloat(editData.minBolehEdit)" x-cloak
                                        class="text-[10px] text-red-500 font-bold mt-1">
                                        Minimal <span x-text="editData.minBolehEdit"></span> karena sebagian sudah
                                        didaur ulang!
                                    </p>
                                </div>
                            </div>

                            {{-- Warning Box --}}
                            <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl flex gap-3">
                                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p
                                    class="text-[10px] text-amber-700 leading-relaxed font-bold uppercase tracking-tighter">
                                    Perubahan jumlah akan otomatis menyesuaikan stok di batch terkait. Harap pastikan
                                    jumlah tidak melebihi kapasitas batch asal.
                                </p>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="editOpen = false"
                                class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                :disabled="parseFloat(editData.jumlah) > parseFloat(editData.maxStok) || parseFloat(editData
                                    .jumlah) < parseFloat(editData.minBolehEdit)"
                                class="px-8 py-4 text-xs font-black uppercase tracking-widest text-white bg-emerald-600 rounded-2xl shadow-xl shadow-emerald-200 disabled:bg-gray-300 disabled:shadow-none transition-all active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
