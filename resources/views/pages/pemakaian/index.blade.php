@php
    // Mencari kategori yang sedang aktif berdasarkan ID di URL
    $currentKategori = $kategoris->find(request('id_kategori'));
    $namaKategori = $currentKategori ? $currentKategori->nama_kategori : 'Operasional';
    $satuan = $currentKategori ? $currentKategori->satuan : 'Semua Satuan';

    // Grouping Pemakaian berdasarkan Tanggal dan ID Kategori
    $groupedPemakaians = $pemakaians->groupBy(function ($item) {
        return $item->tanggal_pemakaian . '-' . $item->id_kategori;
    });
@endphp

<x-layout.beranda.app title="Riwayat Pemakaian {{ $namaKategori }}">
    <div class="md:px-10 py-6 flex flex-col">
        <div class="flex-1 pt-12">

            {{-- 1. HEADER --}}
            <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                {{-- Sisi Kiri: Navigasi & Judul --}}
                <div class="w-full lg:w-auto">
                    <a href="{{ route('beranda') }}"
                        class="group text-cyan-600 hover:text-cyan-700 text-sm font-semibold inline-flex items-center gap-2 transition-all mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Catatan {{ $namaKategori }}
                    </h1>
                    <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">
                        Satuan Pengukuran: <span
                            class="text-cyan-600 font-bold px-2 py-0.5 bg-cyan-50 rounded-md">{{ $satuan }}</span>
                    </p>
                </div>

                {{-- Sisi Kanan: Action Buttons --}}
                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    {{-- Filter Button --}}
                    <button onclick="document.getElementById('filterSidebar').classList.remove('translate-x-full')"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all shadow-sm font-bold text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden xs:inline">Filter</span>
                    </button>

                    {{-- Kelola Kategori --}}
                    @can('kategori-pemakaian.index')
                        <a href="{{ route('kategori-pemakaian.index') }}"
                            class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all shadow-md shadow-amber-100 font-bold text-sm">
                            <span class="whitespace-nowrap">Kelola Kategori</span>
                        </a>
                    @endcan

                    {{-- Tombol Utama --}}
                    @can('pemakaian.create')
                        <button onclick="toggleModal('modalPilihKategori')"
                            class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all shadow-lg shadow-green-100 font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="whitespace-nowrap">Catat Pemakaian</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- MODAL ADAPTIVE --}}
            <div id="modalPilihKategori" class="fixed inset-0 z-[100] hidden overflow-y-auto px-4 py-6 sm:p-0">
                <div class="flex items-end sm:items-center justify-center min-h-screen">
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                        onclick="toggleModal('modalPilihKategori')"></div>

                    <div
                        class="relative bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all overflow-hidden">
                        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-4 sm:hidden"></div>
                        {{-- Drag indicator mobile --}}

                        <h3 class="text-xl font-bold text-gray-900 mb-5 text-center sm:text-left">Pilih Kategori</h3>

                        <div class="grid grid-cols-1 gap-3 max-h-[60vh] overflow-y-auto pr-1">
                            @forelse($kategoris as $kat)
                                <button
                                    onclick="openFormPemakaian('{{ $kat->id }}', '{{ $kat->nama_kategori }}', '{{ $kat->satuan }}')"
                                    class="w-full p-4 border border-gray-100 rounded-xl hover:bg-green-50 hover:border-green-200 transition-all text-left group flex justify-between items-center">
                                    <div>
                                        <span
                                            class="block font-bold text-gray-700 group-hover:text-green-700 uppercase tracking-wide text-sm">{{ $kat->nama_kategori }}</span>
                                        <small class="text-gray-400">Satuan: {{ $kat->satuan }}</small>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-300 group-hover:text-green-500 transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @empty
                                <div class="text-center py-8">
                                    <div
                                        class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada kategori tersedia.</p>
                                </div>
                            @endforelse
                        </div>

                        <button onclick="toggleModal('modalPilihKategori')"
                            class="mt-4 w-full py-3 text-gray-500 font-semibold text-sm sm:hidden">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            {{-- 2. TABEL DATA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">
                                    Jumlah Pemakaian</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">
                                    Total Biaya</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($groupedPemakaians as $group)
                                @php
                                    $firstItem = $group->first();
                                    $totalJumlah = $group->sum('jumlah');
                                    $totalBiaya = $group->sum('total_harga');
                                    $satuan = $firstItem->KategoriPemakaian->satuan ?? '-';
                                @endphp
                                <tr class="hover:bg-cyan-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($firstItem->tanggal_pemakaian)->isoFormat('DD MMMM YYYY') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        <span
                                            class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold uppercase">
                                            {{ $firstItem->KategoriPemakaian->nama_kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-bold text-gray-900">
                                            {{ fmod($totalJumlah, 1) == 0 ? number_format($totalJumlah, 0, ',', '.') : number_format($totalJumlah, 3, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 ml-1">{{ $satuan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-bold text-cyan-700">
                                            Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            @if ($group->count() > 1)
                                                <span
                                                    class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded-lg font-bold">
                                                    {{ $group->count() }} Gabungan
                                                </span>
                                            @else
                                                {{-- Edit --}}
                                                @can('pemakaian.edit')
                                                    <button
                                                        onclick="openEditPemakaian({{ json_encode($firstItem) }}, '{{ $firstItem->KategoriPemakaian->nama_kategori }}', '{{ $satuan }}')"
                                                        class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                @endcan
                                                {{-- Delete --}}
                                                @can('pemakaian.delete')
                                                    <form action="{{ route('pemakaian.destroy', $firstItem->id) }}"
                                                        method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-gray-50 p-4 rounded-full mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">Belum ada data pemakaian
                                                {{ $namaKategori }}.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. SIDEBAR FILTER --}}
    <div id="filterSidebar"
        class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-[60]">
        <div class="h-full flex flex-col p-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-800">Filter Data</h2>
                <button onclick="document.getElementById('filterSidebar').classList.add('translate-x-full')"
                    class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M16.066 8.995a.75.75 0 1 0-1.06-1.061L12 10.939L8.995 7.934a.75.75 0 1 0-1.06 1.06L10.938 12l-3.005 3.005a.75.75 0 0 0 1.06 1.06L12 13.06l3.005 3.006a.75.75 0 0 0 1.06-1.06L13.062 12z" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('pemakaian.index') }}" method="GET" class="flex-1 flex flex-col">

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rentang Tanggal</label>
                        <input type="text" id="date_range" name="date_range" value="{{ request('date_range') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all outline-none text-sm"
                            placeholder="Pilih Tanggal...">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select name="id_kategori"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 outline-none text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('id_kategori') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (auth()->user()->hasRole('Super Admin'))
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Gudang / Perusahaan</label>
                            <select name="id_perusahaan"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 outline-none text-sm">
                                <option value="">Semua Gudang</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="mt-auto flex items-center gap-3">
                    <a href="{{ route('pemakaian.index') }}"
                        class="flex-1 py-3 text-center text-sm font-bold text-gray-500 hover:text-gray-700">Reset</a>
                    <button type="submit"
                        class="flex-1 bg-cyan-600 py-3 rounded-xl text-sm font-bold text-white hover:bg-cyan-700 shadow-lg shadow-cyan-100 transition-all">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Form Input --}}
    <div id="modalTambahPemakaian" class="fixed inset-0 z-[110] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalTambahPemakaian')">
            </div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Input: <span id="labelKategori"
                        class="text-cyan-600"></span></h3>

                <form action="{{ route('pemakaian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_kategori" id="input_id_kategori">

                    <div class="space-y-4">
                        {{-- Role Check untuk Perusahaan --}}
                        @if (auth()->user()->hasRole('Super Admin'))
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Perusahaan</label>
                                <select name="id_perusahaan" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200">
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                                <input type="date" name="tanggal_pemakaian" required value="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (<span
                                        id="labelSatuan"></span>)</label>
                                <input type="number" name="jumlah" step="any" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Per Satuan <span
                                    class="text-gray-300 italic">(Opsional)</span></label>
                            <input type="number" name="harga"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200">
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="toggleModal('modalTambahPemakaian')"
                            class="flex-1 py-3 bg-gray-100 rounded-xl font-bold text-gray-500">Batal</button>
                        <button type="submit"
                            class="flex-1 bg-green-600 text-white rounded-xl font-bold shadow-lg shadow-green-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Edit --}}
    <div id="modalEditPemakaian" class="fixed inset-0 z-[110] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalEditPemakaian')"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Edit: <span id="labelKategoriEdit"
                        class="text-yellow-600"></span></h3>

                <form id="formEditPemakaian" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        @if (auth()->user()->hasRole('Super Admin'))
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Perusahaan</label>
                                <select name="id_perusahaan" id="edit_id_perusahaan" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200">
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                                <input type="date" name="tanggal_pemakaian" id="edit_tanggal_pemakaian" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (<span
                                        id="labelSatuanEdit"></span>)</label>
                                <input type="number" name="jumlah" id="edit_jumlah" step="any" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Per Satuan</label>
                            <input type="number" name="harga" id="edit_harga" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200">
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="toggleModal('modalEditPemakaian')"
                            class="flex-1 py-3 bg-gray-100 rounded-xl font-bold text-gray-500">Batal</button>
                        <button type="submit"
                            class="flex-1 bg-yellow-600 text-white rounded-xl font-bold shadow-lg shadow-yellow-100">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
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

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Kunci scroll layar belakang
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Aktifkan scroll kembali
            }
        }

        function openFormPemakaian(id, nama, satuan) {
            // Tutup modal pilih kategori
            toggleModal('modalPilihKategori');

            // Isi data ke modal form
            document.getElementById('input_id_kategori').value = id;
            document.getElementById('labelKategori').innerText = nama;
            document.getElementById('labelSatuan').innerText = satuan;

            // Buka modal form
            setTimeout(() => {
                toggleModal('modalTambahPemakaian');
            }, 200);
        }

        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        function openEditPemakaian(item, namaKategori, satuan) {
            const form = document.getElementById('formEditPemakaian');

            // Set Action URL dinamis berdasarkan ID item
            form.action = `/pemakaian/${item.id}`;

            // Isi label modal
            document.getElementById('labelKategoriEdit').innerText = namaKategori;
            document.getElementById('labelSatuanEdit').innerText = satuan;

            // Isi nilai input
            document.getElementById('edit_tanggal_pemakaian').value = item.tanggal_pemakaian;
            document.getElementById('edit_jumlah').value = item.jumlah;
            document.getElementById('edit_harga').value = item.harga;

            if (document.getElementById('edit_id_perusahaan')) {
                document.getElementById('edit_id_perusahaan').value = item.id_perusahaan;
            }

            toggleModal('modalEditPemakaian');
        }
    </script>
</x-layout.beranda.app>
