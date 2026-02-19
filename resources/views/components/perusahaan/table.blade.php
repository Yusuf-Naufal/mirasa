@props(['perusahaan'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Informasi Perusahaan
                    </th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Alamat & Kontak</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($perusahaan as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $perusahaan->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-semibold text-gray-900">{{ $i->nama_perusahaan }}</div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $colorClass = match ($i->jenis_perusahaan) {
                                    'Pusat' => 'bg-blue-100 text-blue-800',
                                    'Cabang' => 'bg-amber-100 text-amber-800',
                                    'Anak Perusahaan' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $i->jenis_perusahaan ?? 'Tidak Set' }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span
                                        class="truncate max-w-[200px]">{{ Str::limit($i->alamat ?? 'Alamat belum diisi', 50) }}</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-600 font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-green-500" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                    </svg>
                                    {{ $i->kontak ?? '-' }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                @can('perusahaan.show')
                                    <a href="{{ route('perusahaan.show', $i->id) }}"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2">
                                                <path
                                                    d="M12 5c-6.307 0-9.367 5.683-9.91 6.808a.44.44 0 0 0 0 .384C2.632 13.317 5.692 19 12 19s9.367-5.683 9.91-6.808a.44.44 0 0 0 0-.384C21.368 10.683 18.308 5 12 5" />
                                                <circle cx="12" cy="12" r="3" />
                                            </g>
                                        </svg>
                                    </a>
                                @endcan
                                @can('perusahaan.edit')
                                    <a href="{{ route('perusahaan.edit', $i->id) }}"
                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                @endcan

                                @if ($i->deleted_at == null)
                                    @can('perusahaan.delete')
                                        <form id="delete-form-{{ $i->id }}"
                                            action="{{ route('perusahaan.destroy', $i->id) }}" method="POST">
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
                                    @can('perusahaan.activate')
                                        <form id="aktif-form-{{ $i->id }}"
                                            action="{{ route('perusahaan.activate', $i->id) }}" method="POST">
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
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Data perusahaan belum tersedia</p>
                                @can('perusahaan.create')
                                    <a href="{{ route('perusahaan.create') }}"
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
            {{ $perusahaan->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
