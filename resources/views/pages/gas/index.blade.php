<x-layout.beranda.app title="Riwayat Pemakaian Gas">
    <div class="md:px-10 py-6 flex flex-col">
        <div class="flex-1 pt-12">

            {{-- 1. HEADER --}}
            <div class="mb-8">
                <a href="{{ route('beranda') }}"
                    class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight mt-2">Catatan Pemakaian Gas</h1>
                <p class="text-sm text-gray-500 font-medium">
                    Gudang:
                    <span class="font-bold text-gray-800">
                        @if (request('id_perusahaan'))
                            {{-- Mengambil nama perusahaan dari koleksi $perusahaan berdasarkan filter --}}
                            {{ $perusahaan->firstWhere('id', request('id_perusahaan'))->nama_perusahaan ?? 'Semua Gudang' }}
                        @else
                            {{-- Default: Nama perusahaan user login --}}
                            {{ auth()->user()->perusahaan->nama_perusahaan ?? 'Semua' }}
                        @endif
                    </span>
                </p>
            </div>

            {{-- 2. SEARCH & BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="flex gap-2 items-center w-full">
                    <form action="{{ route('gas.index') }}" method="GET" class="relative w-full md:max-w-md">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari tanggal atau nama bahan..."
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-2xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm text-sm font-medium">
                    </form>

                    <button onclick="openModal('filterModal')"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M9 5a1 1 0 1 0 0 2a1 1 0 0 0 0-2M6.17 5a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-7.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 0 1 0-2zM15 11a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-2.83 0a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-1.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 1 1 0-2zM9 17a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-2.83 0a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-7.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 1 1 0-2z" />
                        </svg>
                        <span class="hidden md:block md:ml-2">Filter</span>
                    </button>
                </div>

                <button onclick="openModal('addModal')"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah
                </button>
            </div>

            {{-- TABLE DAN CARD --}}
            <x-gas.table :gas="$gas" />
            <x-gas.card :gas="$gas" />

            {{-- Loop Modal Edit --}}
            @foreach ($gas as $i)
                <x-gas.edit-modal :i="$i" :supplier="$supplier" />
            @endforeach
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="addModal"
        class="p-2 fixed inset-0 bg-black/50 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6" x-data="{
            // Data Supplier
            supplierOpen: false,
            supplierSearch: '',
            selectedSupplierId: '',
            selectedSupplierName: '',
            suppliers: {{ $supplier->map(fn($s) => ['id' => $s->id, 'name' => $s->nama_supplier])->toJson() }},
        
            jumlah: 0,
            harga: 0,
            get total() { return (this.jumlah * this.harga) },
        
            // Logic Pencarian
            get filteredSuppliers() {
                return this.suppliers.filter(s => s.name.toLowerCase().includes(this.supplierSearch.toLowerCase()))
            },
        
            // Logic Memilih
            selectSupplier(s) {
                this.selectedSupplierId = s.id;
                this.selectedSupplierName = s.name;
                this.supplierSearch = '';
                this.supplierOpen = false;
            }
        }">

            <h2 class="text-lg font-semibold mb-4">Tambah Pemakaian Gas</h2>

            <form action="{{ route('gas.store') }}" method="POST">
                @csrf
                <div class="space-y-3">

                    {{-- Searchable Select: Supplier --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <input type="hidden" name="id_supplier" :value="selectedSupplierId">

                        {{-- Tombol Dropdown --}}
                        <button type="button" @click="supplierOpen = !supplierOpen"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-left text-sm flex justify-between items-center focus:ring-1 focus:ring-blue-500 bg-white">
                            <span :class="selectedSupplierName ? 'text-gray-900' : 'text-gray-400'"
                                x-text="selectedSupplierName || '-- Pilih Supplier --'"></span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- List Item Dropdown --}}
                        <div x-show="supplierOpen" @click.away="supplierOpen = false"
                            class="absolute z-[60] w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden"
                            x-cloak x-transition>
                            <div class="p-2 bg-gray-50 border-b border-gray-100">
                                <input type="text" x-model="supplierSearch" placeholder="Cari supplier..."
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="s in filteredSuppliers" :key="s.id">
                                    <button type="button" @click="selectSupplier(s)"
                                        class="w-full px-3 py-2 text-left text-sm hover:bg-blue-50 hover:text-blue-600 transition-colors flex justify-between items-center">
                                        <span x-text="s.name"></span>
                                        <svg x-show="selectedSupplierId == s.id" class="w-3 h-3 text-blue-500"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Input Tanggal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pemakaian</label>
                        <input type="date" name="tanggal_pemakaian" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Input Jumlah --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah (MMBTU)</label>
                            <input type="number" step="any" name="jumlah_gas" x-model.number="jumlah" required
                                placeholder="0.00"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        {{-- Input Harga Satuan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-xs">Rp</span>
                                <input type="number" name="harga" step="any" x-model.number="harga" required
                                    placeholder="0"
                                    class="w-full border border-gray-200 rounded-lg pl-8 pr-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Display Total (Otomatis) --}}
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-blue-600 uppercase">Estimasi Total Biaya</span>
                            <span class="text-sm font-bold text-blue-700">
                                Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)"></span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Footer Sesuai Contoh Anda --}}
                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" onclick="closeModal('addModal')"
                        class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button
                        class="flex-1 rounded-xl bg-gray-600 py-3 text-sm font-bold text-white hover:bg-gray-800 transition-colors shadow-sm"
                        type="submit">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div id="filterModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60" onclick="closeModal('filterModal')"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl" id="modalContent">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800">Filter Lanjutan</h2>
                <button onclick="closeModal('filterModal')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 15 15">
                        <path fill="currentColor"
                            d="M10.969 3.219a.574.574 0 1 1 .812.812L8.313 7.5l3.468 3.469l.074.09a.575.575 0 0 1-.796.796l-.09-.074L7.5 8.312l-3.469 3.47a.574.574 0 1 1-.812-.813L6.688 7.5l-3.47-3.469l-.073-.09a.575.575 0 0 1 .796-.797l.09.075L7.5 6.687z" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('gas.index') }}" method="GET" class="p-6">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="space-y-5">
                    {{-- Filter Berdasarkan Perusahaan (Hanya Super Admin) --}}
                    @if (auth()->user()->hasRole('Super Admin'))
                        <div>
                            <label for="id_perusahaan"
                                class="block text-sm font-semibold text-gray-700 mb-1">Perusahaan</label>
                            <select name="id_perusahaan" id="id_perusahaan"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FFC829] outline-none">
                                <option value="">Semua Perusahaan</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }} ({{ $p->kota }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Filter Rentang Tanggal --}}
                    <div>
                        <label for="date_range" class="block text-sm font-semibold text-gray-700 mb-1">Rentang
                            Tanggal
                            Masuk</label>
                        <div class="relative">
                            <input type="text" name="date_range" id="date_range"
                                value="{{ request('date_range') }}" placeholder="Pilih rentang tanggal.."
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 outline-none">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('gas.index') }}"
                        class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-600 hover:bg-gray-50">Reset</a>
                    <button type="submit"
                        class="flex-1 rounded-xl bg-gray-600 py-3 text-sm font-bold text-white hover:bg-gray-800 transition-colors shadow-sm">Terapkan
                        Filter</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true,
                altInput: true,
                altFormat: "d M Y",
            });
        });
    </script>

</x-layout.beranda.app>
