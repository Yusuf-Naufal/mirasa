<x-layout.beranda.app title="Riwayat Stok Masuk - {{ $inventory->barang->nama_barang }}">
    <div class="min-h-screen bg-slate-50/50 md:px-10 py-8" x-data="{
        // Modal Edit Full (Lama)
        editModalOpen: false,
        editData: {
            id: '',
            diterima: 0,
            jumlah_rusak: 0,
            stok: 0,
            diskon: 0,
            harga: 0,
            tgl_masuk: '',
            tgl_exp: '',
            no_batch: '',
            tempat: '',
            lokasi: ''
        },
    
        // Modal Quick Action (Baru)
        showActionModal: false,
        modalTitle: '',
        actionType: '',
        activeId: '',
        activeHarga: 0,
        actionUrl: '{{ route('inventory.quick-update') }}',
    
        openEdit(item) {
            this.editData = {
                id: item.id,
                diterima: item.diterima,
                jumlah_diterima: item.diterima,
                jumlah_rusak: item.rusak || 0,
                harga: item.harga,
                tgl_masuk: item.tgl_masuk,
                tgl_exp: item.tgl_exp,
                no_batch: item.no_batch,
                tempat: item.tempat,
                lokasi: item.tempat,
                diskon: item.diskon,
                diskon_lama: item.diskon
            };
            this.editModalOpen = true;
        },
        openAddQty(id) {
            this.activeId = id;
            this.actionType = 'add';
            this.modalTitle = 'Tambah Stok & Penerimaan';
            this.showActionModal = true;
        },
        openReduceQty(id, currentStok) {
            this.activeId = id;
            this.actionType = 'reduce';
            this.modalTitle = 'Penyesuaian Stok Fisik';
            this.activeStokLimit = currentStok;
            this.showActionModal = true;
        },
        openEditPrice(data) {
            this.activeId = data.id;
            this.activeHarga = data.harga;
            this.actionType = 'price';
            this.modalTitle = 'Update Harga Satuan';
            this.showActionModal = true;
        }
    }">

        <div class="mx-auto flex flex-col pt-12 gap-6">
            {{-- Breadcrumb & Header --}}
            <div class="mb-2 px-4 md:px-0">
                <a href="{{ route('inventory.show', $inventory->id) }}"
                    class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Gudang
                </a>
                <div class="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Stok:
                            {{ $inventory->barang->nama_barang }}</h1>
                        <p class="text-slate-500 text-sm mt-1">Total Stok Keseluruhan: <span
                                class="font-bold text-slate-700">{{ number_format($inventory->stok, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Filter & Search --}}
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 mb-6">
                <form action="{{ url()->current() }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    {{-- Search Input --}}
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Cari Data</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Batch / Supplier..."
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition-all active:scale-95 shadow-md shadow-blue-100">
                            Filter
                        </button>
                        @if (request()->anyFilled(['search', 'start_date', 'end_date']))
                            <a href="{{ url()->current() }}"
                                class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all"
                                title="Reset">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table Riwayat --}}
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th
                                    class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center w-16">
                                    No</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Log
                                    Masuk</th>
                                @if (in_array($inventory->barang->jenisBarang->kode, ['BB', 'BP']))
                                    <th
                                        class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">
                                        Supplier</th>
                                @else
                                    <th
                                        class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">
                                        Nomor Batch</th>
                                @endif
                                <th
                                    class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">
                                    Diterima</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">
                                    Stok Aktif</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">
                                    Harga (Rp)</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">
                                    Investasi</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($details as $index => $i)
                                <tr class="hover:bg-blue-50/40 transition-all duration-200 group">
                                    <td class="px-6 py-4 text-sm text-slate-400 text-center font-medium">
                                        {{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($i->tanggal_masuk)->translatedFormat('d M Y') }}</span>
                                            <span
                                                class="text-[10px] text-slate-400">{{ $i->tempat_penyimpanan }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-medium text-slate-600">
                                            @if (in_array($inventory->barang->jenisBarang->kode, ['BB', 'BP']))
                                                {{ $i->Supplier->nama_supplier ?? 'Lainnya' }}
                                            @else
                                                <span
                                                    class="font-mono bg-slate-100 px-2 py-1 rounded text-[11px]">{{ $i->nomor_batch ?? '-' }}</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm font-medium text-slate-600">{{ number_format($i->jumlah_diterima, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-xl ring-1 ring-blue-100">
                                            {{ number_format($i->stok, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm text-slate-600 font-medium">{{ number_format($i->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900">
                                        {{ number_format($i->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div x-data="{ open: false, top: '0px', left: '0px' }" class="relative inline-block text-left">

                                            <button type="button"
                                                @click="
                                                let rect = $event.currentTarget.getBoundingClientRect();
                                                top = (rect.bottom + 4) + 'px';
                                                left = (rect.right - 192) + 'px';
                                                open = !open;
                                            "
                                                @click.away="open = false"
                                                class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all outline-none focus:ring-2 focus:ring-blue-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>

                                            <template x-teleport="body">

                                                <div x-show="open" :style="`top: ${top}; left: ${left};`"
                                                    @scroll.window="open = false"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="fixed w-48 bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 divide-y divide-slate-100 overflow-hidden z-[9999]"
                                                    style="display: none;">

                                                    <div class="py-1">
                                                        @if ($i->stok == $i->jumlah_diterima - $i->jumlah_rusak)
                                                            @can('inventory.detail-edit')
                                                                <button type="button"
                                                                    @click="openEdit({ 
                                                                        id: '{{ $i->id }}', 
                                                                        stok: '{{ $i->stok }}', 
                                                                        diterima: '{{ $i->jumlah_diterima }}', 
                                                                        rusak: '{{ $i->jumlah_rusak ?? 0 }}',
                                                                        harga: '{{ $i->harga }}', 
                                                                        diskon: '{{ $i->diskon ?? 0 }}',
                                                                        tgl_masuk: '{{ $i->tanggal_masuk }}', 
                                                                        no_batch: '{{ $i->nomor_batch }}', 
                                                                        tempat: '{{ $i->tempat_penyimpanan }}', 
                                                                        tgl_exp: '{{ $i->tanggal_exp }}' 
                                                                    }); open = false"
                                                                    class="group flex w-full items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                                                    <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-blue-500"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Edit Data
                                                                </button>
                                                            @endcan

                                                            @can('inventory.delete')
                                                                <div class="py-1">
                                                                    <form
                                                                        action="{{ route('inventory.destroy', $i->id) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Hapus data ini? Rekapitulasi akan disesuaikan.')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="group flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                                            <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                            Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endcan
                                                        @else
                                                            @can('inventory.quick-edit')
                                                                <button type="button"
                                                                    @click="openAddQty('{{ $i->id }}'); open = false"
                                                                    class="group flex w-full items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                                    <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-emerald-500"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    Tambah Stok
                                                                </button>

                                                                <button type="button"
                                                                    @click="openReduceQty('{{ $i->id }}'); open = false"
                                                                    class="group flex w-full items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                                                    <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-rose-500"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M20 12H4" />
                                                                    </svg>
                                                                    Kurangi Stok
                                                                </button>

                                                                <button type="button"
                                                                    @click="openEditPrice({ id: '{{ $i->id }}', harga: '{{ $i->harga }}' }); open = false"
                                                                    class="group flex w-full items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                                    <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-amber-500"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    Ubah Harga
                                                                </button>
                                                            @endcan
                                                        @endif

                                                        @if (in_array($inventory->barang->jenisBarang->kode, ['FG', 'WIP', 'EC']))
                                                            @can('inventory.afkir-ulang')
                                                                <div class="py-1">
                                                                    <a type="submit"
                                                                        href="{{ route('inventory.afkir-ulang', $i->id) }}"
                                                                        class="group flex w-full items-center px-4 py-2.5 text-sm text-yellow-600 hover:bg-yellow-50 transition-colors">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="mr-3 h-4 w-4 text-yellow-400 group-hover:text-yellow-600"
                                                                            viewBox="0 0 512 512">
                                                                            <path fill="currentColor" fill-rule="evenodd"
                                                                                d="M426.667 106.667v42.666L358 149.33c36.077 31.659 58.188 77.991 58.146 128.474c-.065 78.179-53.242 146.318-129.062 165.376s-154.896-15.838-191.92-84.695C58.141 289.63 72.637 204.42 130.347 151.68a85.33 85.33 0 0 0 33.28 30.507a124.59 124.59 0 0 0-46.294 97.066c1.05 69.942 58.051 126.088 128 126.08c64.072 1.056 118.71-46.195 126.906-109.749c6.124-47.483-15.135-92.74-52.236-118.947L320 256h-42.667V106.667zM202.667 64c23.564 0 42.666 19.103 42.666 42.667s-19.102 42.666-42.666 42.666S160 130.231 160 106.667S179.103 64 202.667 64" />
                                                                        </svg>
                                                                        Afkir Ulang
                                                                    </a>
                                                                </div>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </div>

                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 italic">Belum ada
                                        riwayat masuk untuk barang ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <div class="flex justify-end">
                {{ $details->links('vendor.pagination.custom') }}
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="editModalOpen"
                class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-cloak>

                <div @click.away="editModalOpen = false"
                    class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                    {{-- Header Modal --}}
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Edit Riwayat Stok</h3>
                            <p class="text-xs text-slate-500 mt-1">Jenis Barang:
                                {{ $inventory->barang->jenisBarang->nama }}
                                ({{ $inventory->barang->jenisBarang->kode }})</p>
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

                        {{-- --- KONDISI 1: BAHAN BAKU (BB) --- --}}
                        @if ($inventory->barang->jenisBarang->kode == 'BB')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Tanggal
                                        Masuk</label>
                                    <input type="date" name="tanggal_masuk" x-model="editData.tgl_masuk"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-blue-600 uppercase ml-1">Jumlah
                                        Diterima</label>
                                    <input type="number" name="jumlah_diterima" x-model.number="editData.diterima"
                                        class="w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Harga Per
                                        Satuan</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">Rp</span>
                                        <input type="number" name="harga" x-model.number="editData.harga"
                                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-rose-500 uppercase ml-1">Diskon Baru
                                        (%)</label>
                                    <div class="relative">
                                        <input type="number" step="any" name="diskon"
                                            x-model.number="editData.diskon"
                                            class="w-full pl-4 pr-10 py-2.5 bg-rose-50/30 border border-rose-100 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none font-bold text-rose-600 text-sm"
                                            placeholder="0" min="0" max="100">
                                        <span
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-rose-400 font-bold text-xs">%</span>
                                    </div>

                                    <div class="mt-2 px-1 flex justify-between items-center">
                                        <span class="text-[10px] text-gray-500 uppercase font-medium">Saat ini:</span>
                                        <span class="text-sm font-black text-rose-600"
                                            x-text="(editData.diskon_lama || 0) + '%'"></span>
                                    </div>
                                </div>
                            </div>
                            {{-- Sinkronisasi stok otomatis untuk BB --}}
                            <input type="hidden" name="stok" :value="editData.diterima">

                            {{-- --- KONDISI 2: BAHAN PENOLONG (BP) --- --}}
                        @elseif($inventory->barang->jenisBarang->kode == 'BP')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Tanggal
                                        Masuk</label>
                                    <input type="date" name="tanggal_masuk" x-model="editData.tgl_masuk"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Lokasi
                                        Penyimpanan</label>
                                    <input type="text" name="tempat_penyimpanan" x-model="editData.lokasi"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-blue-600 uppercase ml-1">Jumlah
                                        Diterima</label>
                                    <input type="number" name="jumlah_diterima" x-model.number="editData.diterima"
                                        class="w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-red-500 uppercase ml-1">Jumlah Rusak
                                        (Reject)</label>
                                    <input type="number" name="jumlah_rusak" x-model.number="editData.jumlah_rusak"
                                        class="w-full px-4 py-2.5 bg-red-50/30 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 outline-none font-bold text-red-600 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Stok
                                        Bersih</label>
                                    <input type="number" name="stok"
                                        :value="editData.diterima - editData.jumlah_rusak" readonly
                                        class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold cursor-not-allowed text-gray-800">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Harga
                                        Satuan</label>
                                    <input type="number" name="harga" x-model.number="editData.harga"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm">
                                </div>
                            </div>

                            {{-- --- KONDISI 3: ELSE (DEFAULT / LAINNYA) --- --}}
                        @else
                            <div class="grid grid-cols-2 gap-5 mb-8">
                                <div class="col-span-1 space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jumlah
                                        Diterima</label>
                                    <input type="number" name="jumlah_diterima" x-model.number="editData.diterima"
                                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-slate-700 transition-all">
                                </div>
                                <div class="col-span-1 space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga
                                        Satuan (Rp)</label>
                                    <input type="number" name="harga" x-model.number="editData.harga"
                                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-slate-700 transition-all">
                                </div>
                                <div class="col-span-1 space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider text-sm">Tgl
                                        Masuk</label>
                                    <input type="date" name="tanggal_masuk" x-model="editData.tgl_masuk"
                                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 text-sm">
                                </div>
                                <div class="col-span-1 space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider text-sm">Tgl
                                        Expired</label>
                                    <input type="date" name="tanggal_exp" x-model="editData.tgl_exp"
                                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 text-sm">
                                </div>
                                <div class="col-span-1 space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider text-sm">No
                                        Batch</label>
                                    <input type="text" name="nomor_batch" x-model="editData.no_batch"
                                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 text-sm">
                                </div>
                                <div class="col-span-1 space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider text-sm">Tempat</label>
                                    <input type="text" name="tempat_penyimpanan" x-model="editData.tempat"
                                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 text-sm">
                                </div>
                                <input type="hidden" name="stok" :value="editData.diterima">
                            </div>
                        @endif

                        {{-- Footer Modal --}}
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

        {{-- Modal Quick Action --}}
        <template x-teleport="body">
            <div x-show="showActionModal"
                class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                x-cloak>
                <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative"
                    @click.away="showActionModal = false">
                    <form :action="actionUrl" method="POST" class="form-prevent-multiple-submits p-8">
                        @csrf @method('PATCH')
                        <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="modalTitle"></h3>

                        <input type="hidden" name="id" :value="activeId">
                        <input type="hidden" name="type" :value="actionType">

                        <template x-if="actionType === 'add' || actionType === 'reduce'">
                            <div class="space-y-4">
                                <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-500 border border-slate-100"
                                    x-text="actionType === 'add' ? 'Aksi ini akan menambah stok aktif dan jumlah diterima pada log.' : 'Gunakan untuk menyesuaikan jumlah fisik di gudang.'">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1"
                                        x-text="actionType === 'add' ? 'Jumlah Tambah' : 'Jumlah Kurangi'"></label>
                                    <input type="number" name="qty" required min="1"
                                        :max="actionType === 'reduce' ? activeStokLimit : ''"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </template>

                        <template x-if="actionType === 'price'">
                            <div class="space-y-4">
                                <div
                                    class="p-3 bg-amber-50 rounded-xl text-xs text-amber-700 border border-amber-100 italic">
                                    * Harga baru akan memperbarui nilai investasi stok dan riwayat barang keluar.</div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Harga Baru
                                        (Rp)</label>
                                    <input type="number" name="harga" x-model="activeHarga" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </template>

                        <div class="flex gap-3 mt-8">
                            <button type="button" @click="showActionModal = false"
                                class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit"
                                class="btn-submit flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all disabled:opacity-70">

                                <span class="btn-text">Simpan</span>

                                <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                    </form>
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
</x-layout.beranda.app>
