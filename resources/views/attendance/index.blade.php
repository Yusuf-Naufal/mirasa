<x-layout.user.app title="Form Absensi & Gaji">
<div class="min-h-screen bg-gray-50/50 p-6 md:p-10" x-data="{ openEdit: false, openImport: false }">
    <div class="w-full space-y-8">
        
        <!-- NOTIFIKASI -->
        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded-2xl font-semibold text-sm shadow-sm">{{ session('success') }}</div>
        @endif
        <!-- BLOK BARU: RINGKASAN TOTAL GAJI PER KELOMPOK -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Gaji Keseluruhan -->
            <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-3xl shadow-sm">
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Total Gaji Keseluruhan</p>
                <h3 class="text-xl font-black text-indigo-800">
                    Rp {{ number_format($attendances->sum('nominal_gaji'), 0, ',', '.') }}
                </h3>
            </div>

            <!-- Total Gaji Kelompok Langsung -->
            <div class="bg-orange-50 border border-orange-100 p-6 rounded-3xl shadow-sm">
                <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Total Gaji Kelompok Langsung</p>
                <h3 class="text-xl font-black text-orange-800">
                    Rp {{ number_format($attendances->where('kelompok', 'langsung')->sum('nominal_gaji'), 0, ',', '.') }}
                </h3>
            </div>

            <!-- Total Gaji Kelompok Tidak Langsung -->
            <div class="bg-slate-50 border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Gaji Tidak Langsung</p>
                <h3 class="text-xl font-black text-slate-800">
                    Rp {{ number_format($attendances->where('kelompok', 'tidak langsung')->sum('nominal_gaji'), 0, ',', '.') }}
                </h3>
            </div>
        </div>
        <div class="flex items-center gap-2" x-data="{ filename: '' }">
    <form action="{{ route('attendance.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 m-0 p-0">
        @csrf
    </form>
</div>
        <!-- FORM INPUT MANUAL -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h2 class="text-xl font-black text-gray-800 mb-6 uppercase tracking-wide">Input Absensi Manual Karyawan</h2>
            
            <form action="{{ route('attendance.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">ID Karyawan</label>
                    <input type="text" name="id_karyawan" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Karyawan</label>
                    <input type="text" name="nama_karyawan" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Devisi</label>
                    <input type="text" name="devisi" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kelompok</label>
                    <select name="kelompok" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                        <option value="langsung">Langsung</option>
                        <option value="tidak langsung">Tidak Langsung</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Shift</label>
                    <select name="shift" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="non shift">Non Shift</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jam Pulang</label>
                    <input type="time" name="jam_pulang" class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan</label>
                    <select name="keterangan" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal Gaji (Rp)</label>
                    <input type="number" name="nominal_gaji" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                </div>

                <div class="md:col-span-2 lg:col-span-5 flex justify-end items-center pt-3 gap-3">
                    <!-- 1. TOMBOL IMPORT EXCEL (MEMICU OPEN IMPORT) -->
                    <button type="button" onclick="document.getElementById('importModal').style.display = 'flex'" class="px-5 py-3 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold rounded-xl text-sm shadow-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                        </svg>
                        Import Excel
                    </button>

                    <!-- 2. TOMBOL SIMPAN DATA MANUAL YANG TADI HILANG -->
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-md transition-all">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
                    <!-- TAMBAHKAN INDIKATOR JUMLAH PERSONIL REAL-TIME DI SINI -->
                    <div class="ms-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-2 shadow-sm">
                        <!-- Ikon Orang/User SVG -->
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://w3.org">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-2.533-3.076m-10.502 0a4.125 4.125 0 00-2.533 3.076m15.752-3.076a3.951 3.951 0 02-2.159-2.76m-6.02 0a3.951 3.951 0 02-2.159 2.76M5 13.047a3.545 3.545 0 00-2.158 3.01c-.083.422-.023.854.158 1.21a10.114 10.114 0 002.213 2.754m1.118-2.672a3.75 3.75 0 116.5 0m-6.5 0a4.125 4.125 0 118.25 0M18.75 10.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm5.25 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM3.75 10.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z">                              
                            </path>
                        </svg>
                        <span class="text-xs font-black text-blue-800 uppercase tracking-wide">
                            Total: <span class="text-sm text-blue-700 font-extrabold">{{ $attendances->count() }}</span> Personil
                        </span>
                    </div>
        <!-- TABEL DATA HASIL INPUT & TOTAL GAJI INTEGRASI -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            
            <!-- SECTION HEADER DAN FORM FILTER -->
            <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50">
                <div>
                    <h3 class="font-black text-gray-800 uppercase tracking-wide text-sm">Daftar Rekap Absensi</h3>
                    <p class="text-xs text-gray-400 mt-1">Gunakan opsi di samping untuk menyaring data rekapitulasi</p>
                </div>

                {{-- FORM ALAT PENYARING (FILTER) --}}
                <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <!-- Filter Tanggal -->
                    <input type="date" name="filter_tanggal" value="{{ request('filter_tanggal', date('Y-m-d')) }}" 
                           class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm bg-white text-gray-700">

                    <!-- Filter Kelompok -->
                    <select name="filter_kelompok" class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm bg-white text-gray-700">
                        <option value="">Semua Kelompok</option>
                        <option value="langsung" {{ request('filter_kelompok') == 'langsung' ? 'selected' : '' }}>Langsung</option>
                        <option value="tidak langsung" {{ request('filter_kelompok') == 'tidak langsung' ? 'selected' : '' }}>Tidak Langsung</option>
                    </select>

                    <!-- Filter Shift -->
                    <select name="filter_shift" class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm bg-white text-gray-700">
                        <option value="">Semua Shift</option>
                        <option value="A" {{ request('filter_shift') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ request('filter_shift') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="non shift" {{ request('filter_shift') == 'non shift' ? 'selected' : '' }}>Non Shift</option>
                    </select>

                    <!-- Filter Keterangan -->
                    <select name="filter_keterangan" class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm bg-white text-gray-700">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('filter_keterangan') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ request('filter_keterangan') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('filter_keterangan') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>

                    <!-- Tombol Cari Filter -->
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filter
                    </button>
                    {{-- TOMBOL CETAK LAPORAN --}}
                    <a href="{{ route('attendance.print', request()->all()) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1">
                        <!-- Ikon Printer SVG -->
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://w3.org">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.816V8.25m10.56 5.566V8.25m-10.56 5.566h10.56m-10.56 0a6.34 6.34 0 00-2.27-.472 6.545 6.545 0 00-1.579.148 2.25 2.25 0 00-1.6 1.983c-.114.735-.17 1.48-.17 2.234a11.411 11.411 0 00.17 2.234 2.25 2.25 0 001.6 1.983 6.945 6.945 0 001.579.148c.75 0 1.492-.057 2.27-.472m10.56-11.132a6.34 6.34 0 012.27-.472c.524 0 1.045.05 1.56.148a2.25 2.25 0 011.6 1.983c.115.735.17 1.48.17 2.234a11.41 11.41 0 01-.17 2.234 2.25 2.25 0 01-1.6 1.983 6.46 6.46 0 01-1.56.148 6.339 6.339 0 01-2.27-.472m0 0v5.566m0 0a2.25 2.25 0 01-2.25 2.25h-6a2.25 2.25 0 01-2.25-2.25v-5.566M12 3h.008v.008H12V3zm0 3.75h.008v.008H12V6.75z"></path>
                        </svg>
                        Cetak Laporan
                    </a>
                    <!-- Tombol Reset (Kembali ke data awal) -->
                    @if(request()->hasAny(['filter_tanggal', 'filter_kelompok', 'filter_shift', 'filter_keterangan']))
                        <a href="{{ route('attendance.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-xs transition-all">
                            Reset
                        </a>
                    @endif
                    
                </form>
            </div>
            <div class="overflow-x-auto-disabled flex-1">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Devisi</th>
                            <th class="px-6 py-4">Kelompok</th>
                            <th class="px-6 py-4">Shift</th>
                            <th class="px-6 py-4">Masuk / Pulang</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Gaji</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($attendances as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ $item->id_karyawan }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->nama_karyawan }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->devisi }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $item->kelompok == 'langsung' ? 'bg-orange-50 text-orange-600' : 'bg-slate-100 text-slate-600' }}">
                                    {{ strtoupper($item->kelompok) }}
                                </span>
                            </td>
                           <td class="px-6 py-4">
                                @if($item->shift == 'A' || $item->shift == 'B')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-purple-50 text-purple-600">SHIFT {{ $item->shift }}</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600">NON SHIFT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->jam_masuk ?? '-' }} / {{ $item->jam_pulang ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $item->keterangan == 'hadir' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">{{ strtoupper($item->keterangan) }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">Rp {{ number_format($item->nominal_gaji, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button type="button" onclick="openEditModal({{ json_encode($item) }})" class="text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    <form action="{{ route('attendance.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-12 text-gray-400 font-medium bg-white">
                                Belum ada data absensi hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CONTAINER MODAL EDIT DATA (POP-UP) -->
<div id="editModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl space-y-6">
        <div class="flex justify-between items-center border-b pb-4">
            <h3 class="text-lg font-black text-gray-800 uppercase">Edit Data Absensi Karyawan</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="formEditModal" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal</label>
                <input type="date" name="tanggal" id="edit_tanggal" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">ID Karyawan</label>
                <input type="text" name="id_karyawan" id="edit_id_karyawan" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Karyawan</label>
                <input type="text" name="nama_karyawan" id="edit_nama_karyawan" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Devisi</label>
                <input type="text" name="devisi" id="edit_devisi" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kelompok</label>
                <select name="kelompok" id="edit_kelompok" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                    <option value="langsung">Langsung</option>
                    <option value="tidak langsung">Tidak Langsung</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Shift</label>
                <select name="shift" id="edit_shift" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="non shift">Non Shift</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jam Masuk</label>
                <input type="time" name="jam_masuk" id="edit_jam_masuk" class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jam Pulang</label>
                <input type="time" name="jam_pulang" id="edit_jam_pulang" class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan</label>
                <select name="keterangan" id="edit_keterangan" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal Gaji (Rp)</label>
                <input type="number" name="nominal_gaji" id="edit_nominal_gaji" required class="w-full rounded-xl border-gray-200 text-sm p-3 outline-none bg-gray-50">
            </div>
            <div class="md:col-span-2 flex justify-end gap-3 pt-3 border-t">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl text-sm font-bold shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
        <!-- POP-UP MODAL IMPORT EXCEL DATA ABSENSI -->
                <!-- POP-UP MODAL IMPORT EXCEL DATA ABSENSI -->
        <div id="importModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="display: none;">
            
            <div class="bg-white rounded-3xl p-8 max-w-xl w-full shadow-2xl space-y-6">

        <!-- JUDUL MODAL & TOMBOL CLOSE -->
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-black text-gray-800 uppercase tracking-wide">Import Data Absensi Karyawan</h3>
                <p class="text-xs text-gray-400 mt-1">Gunakan file Excel untuk mengunggah banyak data sekaligus.</p>
            </div>
            <button type="button" onclick="document.getElementById('importModal').style.display = 'none'" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- INSTRUKSI ATURAN PENGISIAN (KOTAK BIRU PERSIS GAMBAR) -->
        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 text-xs text-blue-700 space-y-2">
            <p class="font-black uppercase tracking-wider text-blue-800 mb-1">Aturan Pengisian:</p>
            <ul class="list-disc list-inside space-y-1.5 font-medium">
                <li><span class="font-bold">Tanggal</span>: Pastikan format kolom di Excel adalah Date (YYYY-MM-DD).</li>
                <li><span class="font-bold">Kelompok</span>: Cukup isi kata <span class="italic font-bold">langsung</span> atau <span class="italic font-bold">tidak langsung</span>.</li>
                <li><span class="font-bold">Shift</span>: Isi dengan karakter huruf kapital <span class="font-bold">A</span> atau <span class="font-bold">B</span>.</li>
                <li><span class="font-bold">Keterangan</span>: Isi dengan kata status <span class="italic font-bold">hadir</span>, <span class="italic font-bold">izin</span>, atau <span class="italic font-bold">sakit</span>.</li>
                <li>Gunakan baris pertama Excel sebagai header penamaan kolom bawaan.</li>
            </ul>
        </div>

        <!-- FORM UNGGAL BERKAS EXCEL -->
        <form action="{{ route('attendance.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ excelName: '' }">
            @csrf
            
            <!-- AREA SERET & PILIH BERKAS EXCEL (BOX PUTIH ABUP-ABU DI GAMBAR) -->
            <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 hover:border-indigo-400 rounded-2xl p-8 bg-gray-50/50 cursor-pointer transition-all group text-center">
                <input type="file" name="file_excel" required class="hidden" @change="excelName = $event.target.files[0].name">
                
                <!-- Icon Upload Cloud SVG -->
                <div class="p-3 bg-white rounded-xl shadow-sm text-gray-400 group-hover:text-indigo-600 transition-colors mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                    </svg>
                </div>
                
                <span class="text-xs font-bold text-gray-500 group-hover:text-indigo-600 transition-colors" x-text="excelName ? excelName : 'Pilih file Excel (.xlsx)'"></span>
                <span class="text-[10px] text-gray-400 mt-1 font-medium" x-show="!excelName">Maksimal ukuran dokumen 2 MB</span>
            </label>

            <!-- TOMBOL AKSI BAWAH (UNDUH TEMPLATE & PROSES UNGHAH) -->
            <div class="flex justify-between items-center pt-3 border-t">
                <!-- Tombol Unduh Template -->
                <a href="{{ asset('templates/template_absensi.xlsx') }}" download="Template_Import_Absensi_Mirasa.xlsx" class="px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold rounded-xl text-xs flex items-center gap-1.5 transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Template
                </a>

                <!-- Tombol Submit Unggah -->
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition-all">
                    Unggah & Proses Data
                </button>
            </div>
        </form>

    </div>
</div>
{{-- LOGIKA JAVASCRIPT UNTUK MEMBUKA & MENUTUP MODAL POP-UP --}}
<script>
    function openEditModal(data) {
        // 1. Ubah rute URL action form secara dinamis ke URL Update Laravel
        document.getElementById('formEditModal').action = `/attendance/${data.id}`;

        // 2. Masukkan data lama karyawan ke dalam kotak input modal
       document.getElementById('edit_tanggal').value = data.tanggal;
        document.getElementById('edit_id_karyawan').value = data.id_karyawan;
        document.getElementById('edit_nama_karyawan').value = data.nama_karyawan;
        document.getElementById('edit_devisi').value = data.devisi;
        document.getElementById('edit_kelompok').value = data.kelompok;
        document.getElementById('edit_shift').value = data.shift;
        document.getElementById('edit_jam_masuk').value = data.jam_masuk ? data.jam_masuk.substring(0, 5) : '';
        document.getElementById('edit_jam_pulang').value = data.jam_pulang ? data.jam_pulang.substring(0, 5) : '';
        document.getElementById('edit_keterangan').value = data.keterangan;
        document.getElementById('edit_nominal_gaji').value = Math.round(data.nominal_gaji);

        // 3. Munculkan modal pop-up ke layar
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        // Sembunyikan kembali modal pop-up
        const modal = document.getElementById('editModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
{{-- GAYA KHUSUS SAAT KERTAS DICETAK (PRINT MODE) --}}
<style>
    @media print {
        /* 1. Sembunyikan Navigasi Samping, Atas, Formulir Input, dan Tombol Aksi */
        nav, aside, #asideSekunder, .bg-white.p-8.rounded-3xl, form, .px-6.py-4.text-center, td:last-child, th:last-child {
            display: none !important;
        }
        
        /* 2. Atur agar konten tabel melebar penuh di kertas kertas */
        body, .min-h-screen, .max-w-7xl {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* 3. Hilangkan efek bayangan box shadow saat dicetak */
        .shadow-sm, .rounded-3xl {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
</x-layout.user.app>
