@props(['jenis'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Jenis
                    </th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($jenis as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $jenis->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-semibold text-gray-900">{{ $i->nama_jenis }}</div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-semibold text-gray-700">{{ $i->kode }}</div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                {{-- TOMBOL EDIT --}}
                                <button type="button" onclick="openModal('editModal-{{ $i->id }}')"
                                    class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                    title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                {{-- LOGIKA DELETE / AKTIFKAN --}}
                                <form id="delete-form-{{ $i->id }}"
                                    action="{{ route('barang.jenis.destroy', $i->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $i->id }})"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    <div id="editModal-{{ $i->id }}"
                        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[9999] p-4">
                        <div
                            class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 text-left whitespace-normal relative">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800">Edit Data Jenis</h2>

                            <form action="{{ route('barang.jenis.update', $i->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4 mb-6 text-left">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jenis</label>
                                        <input type="text" name="nama_jenis" required
                                            value="{{ old('nama_jenis', $i->nama_jenis) }}"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                                        <input type="text" name="kode" required
                                            value="{{ old('kode', $i->kode) }}"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 uppercase">
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="closeModal('editModal-{{ $i->id }}')"
                                        class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg">Batal</button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm bg-[#0D1630] hover:bg-[#FFC829] hover:text-[#0D1630] text-white rounded-lg font-medium">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mb-3"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M2 3h20v18H2zm18 16V5H4v14zM8 7H6v2h2zm2 0h8v2h-8zm-2 4H6v2h2zm2 0h8v2h-8zm-2 4H6v2h2zm2 0h8v2h-8z" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Data jenis belum tersedia</p>
                                <button type="button" onclick="openModal('addModal')"
                                    class="mt-4 text-blue-500 text-xs font-bold uppercase tracking-wider hover:underline">Tambah
                                    Sekarang</button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="hidden md:block">
    <!-- Pagination -->
    <div class="mt-6">
        <div class="flex justify-end">
            {{ $jenis->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
