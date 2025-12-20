@props(['barang'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Perusahaan</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($barang as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $barang->firstItem() + $index }}
                        </td>

                        {{-- KOLOM FOTO --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="h-12 w-12 rounded-lg overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                                @if ($i->foto)
                                    <img src="{{ asset('storage/' . $i->foto) }}" alt="{{ $i->nama_barang }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-300">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-900">{{ $i->nama_barang }}</div>
                                {{-- KODE BARANG DI BAWAH NAMA --}}
                                <div
                                    class="text-xs font-mono text-gray-500 mt-0.5 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 w-fit">
                                    {{ $i->kode }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-semibold text-gray-700">{{ $i->Perusahaan->nama_perusahaan }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                {{-- TOMBOL EDIT --}}
                                <a type="button" href="{{ route('barang.index.edit', $i->id) }}"
                                    class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                    title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                {{-- LOGIKA DELETE --}}
                                <form id="delete-form-{{ $i->id }}"
                                    action="{{ route('barang.index.destroy', $i->id) }}" method="POST">
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
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mb-3"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M2 3h20v18H2zm18 16V5H4v14zM8 7H6v2h2zm2 0h8v2h-8zm-2 4H6v2h2zm2 0h8v2h-8zm-2 4H6v2h2zm2 0h8v2h-8z" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Data barang belum tersedia</p>
                                <a href="{{ route('barang.index.create') }}"
                                    class="mt-4 text-blue-500 text-xs font-bold uppercase tracking-wider hover:underline">Tambah
                                    Sekarang</a>
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
            {{ $barang->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
