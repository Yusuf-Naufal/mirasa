@props(['proses'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Proses</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Perusahaan</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($proses as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $proses->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-900">{{ $i->nama_proses ?? 'No Name' }}
                                </div>
                                {{-- KODE BARANG DI BAWAH NAMA --}}
                                <div
                                    class="text-xs font-mono text-gray-500 mt-0.5 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 w-fit">
                                    {{ $i->kode ?? 'No Kode' }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-700">
                                    {{ $i->perusahaan->nama_perusahaan ?? 'No Company' }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $i->perusahaan->kota ?? 'No City' }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                @can('proses.edit')
                                    {{-- TOMBOL EDIT --}}
                                    <button type="button" onclick="openModal('editModal-{{ $i->id }}')"
                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                @endcan

                                {{-- LOGIKA DELETE --}}
                                @if ($i->deleted_at == null)
                                    @can('proses.delete')
                                        <form id="delete-form-{{ $i->id }}"
                                            action="{{ route('proses.destroy', $i->id) }}" method="POST">
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
                                    @endcan
                                @else
                                    @can('proses.activate')
                                        <form id="aktif-form-{{ $i->id }}"
                                            action="{{ route('proses.activate', $i->id) }}" method="POST">
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
                                    @endcan
                                @endif

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
                                        d="M19.5 12c0-.23-.01-.45-.03-.68l1.86-1.41c.4-.3.51-.86.26-1.3l-1.87-3.23a.987.987 0 0 0-1.25-.42l-2.15.91c-.37-.26-.76-.49-1.17-.68l-.29-2.31c-.06-.5-.49-.88-.99-.88h-3.73c-.51 0-.94.38-1 .88l-.29 2.31c-.41.19-.8.42-1.17.68l-2.15-.91c-.46-.2-1-.02-1.25.42L2.41 8.62c-.25.44-.14.99.26 1.3l1.86 1.41a7.3 7.3 0 0 0 0 1.35l-1.86 1.41c-.4.3-.51.86-.26 1.3l1.87 3.23c.25.44.79.62 1.25.42l2.15-.91c.37.26.76.49 1.17.68l.29 2.31c.06.5.49.88.99.88h3.73c.5 0 .93-.38.99-.88l.29-2.31c.41-.19.8-.42 1.17-.68l2.15.91c.46.2 1 .02 1.25-.42l1.87-3.23c.25-.44.14-.99-.26-1.3l-1.86-1.41c.03-.23.04-.45.04-.68m-7.46 3.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5s3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Data proses belum tersedia</p>
                                @can('proses.create')
                                    <button type="button" onclick="openModal('addModal')"
                                        class="mt-4 text-blue-500 text-xs font-bold uppercase tracking-wider hover:underline">Tambah
                                        Sekarang</button>
                                @endcan
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
            {{ $proses->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
