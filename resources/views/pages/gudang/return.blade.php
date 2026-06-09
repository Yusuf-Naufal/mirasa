<x-layout.beranda.app title="Daftar Barang Return">
    <div class="min-h-screen bg-slate-50/50 pb-20" x-data="{
        // Modal Edit Single
        showModal: false,
        activeId: '',
        activeStatus: '',
        actionUrl: '',
    
        // Modal & State Bulk Action
        showBulkModal: false,
        bulkStatus: 'DIPROSES',
        selectedItems: [],
    
        get allSelected() {
            return this.selectedItems.length > 0 && this.selectedItems.length === {{ count($returns) }};
        },
        toggleAll(e) {
            if (e.target.checked) {
                this.selectedItems = {{ json_encode($returns->pluck('id')->map(fn($id) => (string) $id)) }};
            } else {
                this.selectedItems = [];
            }
        },
    
        openModal(id, currentStatus) {
            this.activeId = id;
            this.activeStatus = currentStatus || 'PENDING';
            this.actionUrl = '/return-barang/' + id + '/status'; // Sesuaikan route jika beda
            this.showModal = true;
        }
    }">

        <div class="max-w-7xl mx-auto pt-20 flex flex-col gap-6">
            
            {{-- Breadcrumb & Header --}}
            <div class="flex flex-col gap-2">
                <a href="{{ route('inventory.index') }}"
                    class="w-max group text-blue-600 hover:text-blue-800 text-sm font-bold inline-flex items-center gap-2 transition-all bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Gudang
                </a>
                <div class="mt-2 flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                            Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-rose-500">Return Barang</span>
                        </h1>
                        <p class="text-slate-500 text-sm mt-1.5 md:mt-2 font-medium">Pantau dan perbarui status pengembalian logistik ke supplier Anda.</p>
                    </div>
                </div>
            </div>

            {{-- Filter Status (Modern Tabs) --}}
            <div class="bg-white rounded-2xl md:rounded-3xl border border-slate-200/80 shadow-sm p-4 md:p-6 flex flex-col xl:flex-row gap-4 lg:items-center justify-between">
                <div class="flex items-center gap-3 shrink-0">
                    <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Filter Data</span>
                        <span class="block text-sm font-bold text-slate-700">Berdasarkan Status</span>
                    </div>
                </div>

                {{-- Horizontal Scrollable Tabs on Mobile --}}
                <div class="w-full overflow-x-auto custom-scrollbar pb-2 xl:pb-0 -mb-2 xl:mb-0">
                    <div class="flex flex-nowrap lg:flex-wrap gap-2 min-w-max xl:min-w-0 xl:justify-end">
                        @foreach (['PENDING', 'DIPROSES', 'SELESAI', 'DITOLAK'] as $status)
                            @php
                                $isActive = request('status') === $status;
                                $colorClass = match($status) {
                                    'PENDING' => $isActive ? 'bg-amber-500 text-white border-amber-500 shadow-amber-200' : 'hover:bg-amber-50 text-slate-600 border-slate-200 hover:border-amber-300 hover:text-amber-700',
                                    'DIPROSES' => $isActive ? 'bg-blue-500 text-white border-blue-500 shadow-blue-200' : 'hover:bg-blue-50 text-slate-600 border-slate-200 hover:border-blue-300 hover:text-blue-700',
                                    'SELESAI' => $isActive ? 'bg-emerald-500 text-white border-emerald-500 shadow-emerald-200' : 'hover:bg-emerald-50 text-slate-600 border-slate-200 hover:border-emerald-300 hover:text-emerald-700',
                                    'DITOLAK' => $isActive ? 'bg-rose-500 text-white border-rose-500 shadow-rose-200' : 'hover:bg-rose-50 text-slate-600 border-slate-200 hover:border-rose-300 hover:text-rose-700',
                                };
                            @endphp
                            <a href="{{ route('inventory.return-barang', ['status' => $status]) }}"
                                class="px-5 py-2.5 rounded-xl text-sm font-bold border transition-all shadow-sm whitespace-nowrap {{ $colorClass }}">
                                {{ $status }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Table Card --}}
            <div class="bg-white rounded-2xl md:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col relative z-10">
                
                {{-- Action Bar Info (Muncul jika ada yg diceklis) --}}
                <div x-show="selectedItems.length > 0" x-collapse class="bg-blue-50/50 border-b border-blue-100 px-6 py-3 flex items-center justify-between">
                    <span class="text-sm font-bold text-blue-800"><span x-text="selectedItems.length"></span> baris dipilih</span>
                    <button type="button" @click="selectedItems = []" class="text-xs font-bold text-blue-600 hover:text-blue-800 underline">Batalkan Pilihan</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-6 py-5 w-12 text-center">
                                    <div class="inline-flex items-center justify-center">
                                        <input type="checkbox" :checked="allSelected" @change="toggleAll"
                                            class="w-5 h-5 rounded-md text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer shadow-sm transition-all">
                                    </div>
                                </th>
                                <th class="px-4 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Penerimaan & Batch</th>
                                <th class="px-4 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Info Logistik</th>
                                <th class="px-4 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Jml Return</th>
                                <th class="px-4 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status Terkini</th>
                                <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @forelse($returns as $index => $i)
                                <tr class="hover:bg-slate-50/80 transition-all duration-200"
                                    :class="selectedItems.includes('{{ $i->id }}') ? 'bg-blue-50/40 hover:bg-blue-50/60' : ''">
                                    
                                    {{-- Checkbox --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center">
                                            <input type="checkbox" value="{{ $i->id }}" x-model="selectedItems"
                                                class="w-5 h-5 rounded-md text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer shadow-sm transition-all">
                                        </div>
                                    </td>

                                    {{-- Penerimaan & Batch --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-slate-50 rounded-lg text-slate-400 border border-slate-100 hidden sm:block">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($i->tanggal_masuk)->translatedFormat('d M Y') }}</span>
                                                <span class="text-[11px] font-medium text-slate-500 mt-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                    {{ $i->nomor_batch ?? 'Tanpa Batch' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Info Logistik (Barang & Supplier) --}}
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-blue-700 leading-tight">{{ $i->Inventory->Barang->nama_barang ?? 'Barang Terhapus' }}</span>
                                            <span class="text-[11px] font-bold text-slate-500 uppercase mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $i->Supplier->nama_supplier ?? 'Internal' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Jumlah Return --}}
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 text-sm font-black text-orange-600 bg-orange-50 px-3 py-1.5 rounded-xl border border-orange-100 shadow-sm">
                                            {{ number_format($i->jumlah_return, 0, ',', '.') }}
                                            <span class="text-[10px] uppercase font-bold text-orange-400">{{ $i->Inventory->Barang->satuan ?? '' }}</span>
                                        </span>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="px-4 py-4 text-center">
                                        @php
                                            $status = $i->status_return ?? 'PENDING';
                                            $badgeConfig = match ($status) {
                                                'SELESAI' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                                'DIPROSES' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
                                                'DITOLAK' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500'],
                                                default => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                                            };
                                        @endphp
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border shadow-sm {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} {{ $badgeConfig['border'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $badgeConfig['dot'] }} animate-pulse"></span>
                                            <span class="text-[11px] font-black tracking-wide uppercase">{{ $status }}</span>
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" @click="openModal('{{ $i->id }}', '{{ $status }}')"
                                            class="group inline-flex items-center justify-center p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 hover:shadow-md hover:shadow-blue-100 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-inner">
                                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <h4 class="text-slate-800 font-bold text-lg">Tidak ada data return</h4>
                                            <p class="text-slate-400 text-sm mt-1">Belum ada barang yang tercatat pada filter status ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Styling Terintegrasi --}}
                @if($returns->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $returns->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- FLOATING ACTION BAR (Untuk Update Massal) --}}
        <div x-show="selectedItems.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-10 scale-95"
             x-cloak
             class="fixed bottom-6 md:bottom-10 left-1/2 transform -translate-x-1/2 z-[100] bg-slate-800/90 backdrop-blur-md text-white px-4 md:px-6 py-3 md:py-4 rounded-2xl md:rounded-[2rem] shadow-2xl shadow-slate-900/50 flex items-center gap-4 md:gap-6 border border-slate-700 w-[92%] max-w-xl ring-1 ring-white/10">
            
            <div class="flex flex-col flex-1 min-w-0">
                <span class="font-black text-sm md:text-base tracking-wide text-white truncate">
                    <span x-text="selectedItems.length" class="text-blue-400"></span> Data Terpilih
                </span>
                <span class="text-[10px] md:text-xs text-slate-400 font-medium truncate">Pilih opsi untuk perbarui massal</span>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" @click="showBulkModal = true"
                    class="bg-blue-600 hover:bg-blue-500 px-4 md:px-6 py-2.5 rounded-xl md:rounded-2xl text-xs md:text-sm font-bold transition-all shadow-lg shadow-blue-600/30 active:scale-95">
                    Ubah Status
                </button>
                <button type="button" @click="selectedItems = []" 
                    class="text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-600 p-2.5 rounded-xl md:rounded-2xl transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        {{-- MODAL UBAH STATUS MASSAL --}}
        <template x-teleport="body">
            <div x-show="showBulkModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm overflow-hidden relative transform transition-all" 
                     @click.away="showBulkModal = false"
                     x-show="showBulkModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-blue-50/50">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Update Massal</h3>
                            <p class="text-xs text-blue-600 font-bold mt-0.5"><span x-text="selectedItems.length"></span> Baris Terpilih</p>
                        </div>
                        <button @click="showBulkModal = false" class="p-2 bg-white rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shadow-sm border border-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form action="{{ route('inventory.return-barang.bulk-update') }}" method="POST" class="p-6 form-prevent-multiple-submits">
                        @csrf @method('PATCH')

                        <template x-for="id in selectedItems" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>

                        <div class="space-y-2 mb-8">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Terapkan Status Ke</label>
                            <div class="relative">
                                <select name="status_return" x-model="bulkStatus" required
                                    class="w-full pl-4 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-bold text-slate-700 shadow-inner cursor-pointer transition-colors appearance-none">
                                    <option value="PENDING">Pending Return</option>
                                    <option value="DIPROSES">Diproses (Sedang dikirim)</option>
                                    <option value="SELESAI">Selesai (Sdh diganti/Klaim)</option>
                                    <option value="DITOLAK">Ditolak Supplier (Rugi)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showBulkModal = false" class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-all text-sm active:scale-95">Batal</button>
                            <button type="submit" class="btn-submit flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all text-sm active:scale-95">
                                <span class="btn-text">Terapkan</span>
                                <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- MODAL UBAH STATUS SINGLE --}}
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm overflow-hidden relative transform transition-all" 
                     @click.away="showModal = false"
                     x-show="showModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">Ubah Status Return</h3>
                        <button @click="showModal = false" class="p-2 bg-white rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shadow-sm border border-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form :action="actionUrl" method="POST" class="p-6 form-prevent-multiple-submits">
                        @csrf @method('PATCH')

                        <div class="space-y-2 mb-8">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Pilih Status Baru</label>
                            <div class="relative">
                                <select name="status_return" x-model="activeStatus" required
                                    class="w-full pl-4 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-bold text-slate-700 shadow-inner cursor-pointer transition-colors appearance-none">
                                    <option value="PENDING">Pending Return</option>
                                    <option value="DIPROSES">Diproses (Sedang dikirim)</option>
                                    <option value="SELESAI">Selesai (Sdh diganti/Klaim)</option>
                                    <option value="DITOLAK">Ditolak Supplier (Rugi)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showModal = false" class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-all text-sm active:scale-95">Batal</button>
                            <button type="submit" class="btn-submit flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all text-sm active:scale-95">
                                <span class="btn-text">Simpan Status</span>
                                <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('.form-prevent-multiple-submits');
            if (form && form.checkValidity()) {
                const btn = form.querySelector('.btn-submit');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-not-allowed');
                    const btnText = form.querySelector('.btn-text');
                    const btnSpinner = form.querySelector('.btn-spinner');
                    if (btnText) btnText.innerText = "Menyimpan...";
                    if (btnSpinner) btnSpinner.classList.remove('hidden');
                }
            }
        });
    </script>
</x-layout.beranda.app>