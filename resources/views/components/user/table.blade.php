@props(['user'])

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-600 text-white">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($user as $index => $i)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-gray-900">{{ $i->name }}</div>
                                {{-- KODE BARANG DI BAWAH NAMA --}}
                                <div
                                    class="text-xs font-mono text-gray-500 mt-0.5 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 w-fit">
                                    {{ $i->Perusahaan->nama_perusahaan ?? 'All Akses' }}
                                    ({{ $i->Perusahaan->kota ?? 'XX' }})
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-semibold text-gray-700">{{ $i->username }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($i->roles->count() > 0)
                                @php $role = $i->roles->first(); @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
            {{ $role->name == 'Super Admin' ? 'bg-purple-100 text-purple-800' : '' }}
            {{ $role->name == 'Manager' ? 'bg-blue-100 text-blue-800' : '' }}
            {{ $role->name == 'QC' ? 'bg-amber-100 text-amber-800' : '' }}
            {{ in_array($role->name, ['KA Kupas', 'Kepala Produksi']) ? 'bg-red-100 text-red-800' : '' }}
            {{ in_array($role->name, ['Admin Gudang', 'Admin Kantor']) ? 'bg-green-100 text-green-800' : '' }}
            {{ !in_array($role->name, ['Super Admin', 'Manager', 'QC', 'KA Kupas', 'Kepala Produksi', 'Admin Gudang', 'Admin Kantor']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $role->name }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400 italic">
                                    Tanpa Akses
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center gap-3">
                                {{-- TOMBOL EDIT --}}
                                @can('user.edit')
                                    <a type="button" href="{{ route('user.edit', $i->id) }}"
                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                        title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                @endcan

                                {{-- LOGIKA DELETE --}}
                                @can('user.delete')
                                    <form id="delete-form-{{ $i->id }}"
                                        action="{{ route('user.destroy', $i->id) }}" method="POST">
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
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Data user belum tersedia</p>
                                @can('user.create')
                                    <a href="{{ route('user.create') }}"
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
            {{ $user->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
