@props(['supplier'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            {{-- Header Tabel --}}
            <thead class="bg-gray-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Nama Supplier</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Kode</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Kategori</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>

            {{-- Body Tabel --}}
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($supplier as $key => $i)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- No --}}
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-left">
                            {{ $loop->iteration }}
                        </td>
                        
                        {{-- Nama Supplier --}}
                        <td class="px-6 py-4 font-bold text-gray-900 text-center uppercase">
                            {{ $i['nama'] ?? '-' }}
                        </td>
                        
                        {{-- Kode --}}
                        <td class="px-6 py-4 text-gray-600 text-center">
                            {{ $i['kode'] ?? '-' }}
                        </td>

                        {{-- Kategori --}}
                        <td class="px-6 py-4 text-gray-600 text-center">
                            {{ $i['kategori'] ?? '-' }}
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-3">
                                {{-- Tombol Edit --}}
                                <button type="button" onclick="openModal('editModal-{{ $key }}')"
                                    class="rounded-lg bg-yellow-50 p-2 text-yellow-600 hover:bg-yellow-100 transition-colors border border-yellow-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('supplier.destroy', $key) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors border border-red-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-sm italic">Belum ada data supplier.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>