<x-layout.beranda.app title="Kelola Kategori Pemakaian">
    <div class="md:px-10 py-6">
        <div class="pt-12">
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <a href="{{ route('pemakaian.index') }}"
                        class="text-cyan-600 hover:text-cyan-700 text-sm font-semibold inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Kategori Pemakaian</h1>
                    <p class="text-sm text-gray-500">Kelola master data kategori seperti Listrik, Gas, atau Air.</p>
                </div>
                @can('kategori-pemakaian.create')
                    <button onclick="toggleModal('modalTambahKategori')"
                        class="bg-cyan-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:bg-cyan-700 transition-all">
                        + Tambah Kategori
                    </button>
                @endcan
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Nama Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Satuan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($kategoris as $k)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-700">{{ $k->nama_kategori }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="bg-cyan-100 text-cyan-700 px-2 py-1 rounded text-xs font-bold">
                                        {{ $k->satuan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center flex justify-center gap-3">
                                    {{-- TOMBOL EDIT --}}
                                    @can('kategori-pemakaian.edit')
                                        <button type="button" onclick="openEditModal({{ json_encode($k) }})"
                                            class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    @endcan

                                    {{-- TOMBOL DELETE (Soft Delete) --}}
                                    @can('kategori-pemakaian.delete')
                                        <form action="{{ route('kategori-pemakaian.destroy', $k->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-600 p-2 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">Belum ada kategori
                                    terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Kategori --}}
    <div id="modalTambahKategori" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalTambahKategori')"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Tambah Kategori Baru</h3>
                <form action="{{ route('kategori-pemakaian.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @if (auth()->user()->hasRole('Super Admin'))
                            <div class="space-y-1">
                                <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan
                                    <span class="text-red-500">*</span></label>
                                <select name="id_perusahaan" required
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:border-cyan-500 transition-colors border bg-white">
                                    <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" placeholder="Contoh: LISTRIK" required
                                class="w-full uppercase px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Satuan</label>
                            <input type="text" name="satuan" placeholder="Contoh: kWh / m3" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all">
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="toggleModal('modalTambahKategori')"
                            class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit"
                            class="flex-1 py-3 text-sm font-bold text-white bg-cyan-600 rounded-xl hover:bg-cyan-700 shadow-lg shadow-cyan-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Kategori --}}
    <div id="modalEditKategori" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalEditKategori')"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Edit Kategori</h3>
                <form id="formEditKategori" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        @if (auth()->user()->hasRole('Super Admin'))
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Perusahaan</label>
                                <select name="id_perusahaan" id="edit_id_perusahaan" required
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm border bg-white">
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="edit_nama_kategori" required
                                class="w-full uppercase px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Satuan</label>
                            <input type="text" name="satuan" id="edit_satuan" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-500/20 outline-none">
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="toggleModal('modalEditKategori')"
                            class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit"
                            class="flex-1 py-3 text-sm font-bold text-white bg-cyan-600 rounded-xl hover:bg-cyan-700 shadow-lg shadow-cyan-100">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        function openEditModal(kategori) {
            const modal = document.getElementById('modalEditKategori');
            const form = document.getElementById('formEditKategori');

            // Set URL action dinamis
            form.action = `/kategori-pemakaian/${kategori.id}`;

            // Isi field input
            document.getElementById('edit_nama_kategori').value = kategori.nama_kategori;
            document.getElementById('edit_satuan').value = kategori.satuan;

            // Set perusahaan jika role super admin
            const selectPerusahaan = document.getElementById('edit_id_perusahaan');
            if (selectPerusahaan) {
                selectPerusahaan.value = kategori.id_perusahaan;
            }

            modal.classList.remove('hidden');
        }
    </script>
</x-layout.beranda.app>
