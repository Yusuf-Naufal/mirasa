@props(['costumer'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Costumer
                    </th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Perusahaan</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($costumer as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $costumer->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                <div class="text-sm font-bold text-gray-800 tracking-tight leading-none">
                                    {{ $i->nama_costumer }}
                                </div>

                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex items-center bg-gray-50 text-gray-700 px-2 py-0.5 rounded-md border border-gray-100 shadow-sm">
                                        <span class="text-[10px] font-mono font-bold tracking-wider uppercase">
                                            {{ $i->kode }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-semibold text-gray-700">
                                    {{ $i->perusahaan->nama_perusahaan ?? 'No' }}</div>
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
                                @if ($i->deleted_at == null)
                                    <form id="delete-form-{{ $i->id }}"
                                        action="{{ route('costumer.destroy', $i->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $i->id }})"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form id="aktif-form-{{ $i->id }}"
                                        action="{{ route('costumer.activate', $i->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="confirmActivate('{{ $i->id }}')"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 12 12">
                                                <path fill="currentColor"
                                                    d="M9.765 3.205a.75.75 0 0 1 .03 1.06l-4.25 4.5a.75.75 0 0 1-1.075.015L2.22 6.53a.75.75 0 0 1 1.06-1.06l1.705 1.704l3.72-3.939a.75.75 0 0 1 1.06-.03" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif


                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mb-3"
                                    viewBox="0 0 12 12">
                                    <path fill="currentColor"
                                        d="M6.153 7.008A1.5 1.5 0 0 1 7.5 8.5c0 .771-.47 1.409-1.102 1.83c-.635.424-1.485.67-2.398.67s-1.763-.246-2.398-.67C.969 9.91.5 9.271.5 8.5A1.5 1.5 0 0 1 2 7h4zM10.003 7a1.5 1.5 0 0 1 1.5 1.5c0 .695-.432 1.211-.983 1.528c-.548.315-1.265.472-2.017.472q-.38-.001-.741-.056c.433-.512.739-1.166.739-1.944A2.5 2.5 0 0 0 7.997 7zM4.002 1.496A2.253 2.253 0 1 1 4 6.001a2.253 2.253 0 0 1 0-4.505m4.75 1.001a1.75 1.75 0 1 1 0 3.5a1.75 1.75 0 0 1 0-3.5" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Data costumer belum tersedia</p>
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
            {{ $costumer->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
