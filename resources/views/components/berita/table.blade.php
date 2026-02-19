@props(['berita'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($berita as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $berita->firstItem() + $index }}
                        </td>

                        {{-- KOLOM FOTO --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="h-12 w-12 rounded-lg overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                                @if ($i->gambar_utama)
                                    <img src="{{ asset('storage/' . $i->gambar_utama) }}" alt="{{ $i->judul }}"
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
                            <div class="flex flex-col gap-1.5">
                                <div class="text-sm font-bold text-gray-800 tracking-tight leading-none">
                                    {{ $i->judul }}
                                </div>

                            </div>
                        </td>

                        {{-- KOLOM STATUS & UNGGULAN --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-2">
                                {{-- Status Aktif/Draft --}}
                                <div>
                                    @if ($i->status_publish)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                            Public
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                            Private
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                {{-- TOMBOL EDIT --}}
                                @can('berita.edit')
                                    <a type="button" href="{{ route('berita.edit', $i->id) }}"
                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                @endcan

                                @can('berita.delete')
                                    <form id="delete-form-{{ $i->id }}"
                                        action="{{ route('berita.destroy', $i->id) }}" method="POST">
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
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mb-3"
                                    viewBox="0 0 2048 2048">
                                    <path fill="currentColor"
                                        d="M2048 512v896q0 53-20 99t-55 81t-82 55t-99 21H249q-51 0-96-20t-79-53t-54-79t-20-97V256h1792v256zm-128 128h-128v704q0 26-19 45t-45 19t-45-19t-19-45V384H128v1031q0 25 9 47t26 38t39 26t47 10h1543q27 0 50-10t40-27t28-41t10-50zm-384 0H256V512h1280zm0 768h-512v-128h512zm0-256h-512v-128h512zm0-256h-512V768h512zm-640 512H256V765h640zm-512-128h384V893H384z" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Berita belum tersedia</p>
                                @can('berita.create')
                                    <a href="{{ route('berita.create') }}"
                                        class="mt-4 text-blue-500 text-xs font-bold uppercase tracking-wider hover:underline">Tambah
                                        Sekarang</a>
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
            {{ $berita->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
