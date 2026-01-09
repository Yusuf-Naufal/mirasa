@props(['user'])

<div x-data="{ openCardId: null, openDropdownId: null }" class="space-y-4 md:hidden">
    @forelse ($user as $index => $i)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 transition-all">

            <div class="flex justify-between items-start gap-3">
                {{-- Area Klik untuk Expand --}}
                <div class="flex gap-3 cursor-pointer flex-1 min-w-0"
                    @click="openCardId = (openCardId === {{ $i->id }} ? null : {{ $i->id }})">

                    <div class="flex-shrink-0">
                        {{-- AVATAR USER --}}
                        <div class="h-14 w-14 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm bg-gray-50 flex items-center justify-center">
                            @if ($i->foto)
                                <img src="{{ asset('storage/' . $i->foto) }}" alt="{{ $i->name }}"
                                    class="h-full w-full object-cover">
                            @else
                                <div class="bg-green-100 text-green-600 h-full w-full flex items-center justify-center font-bold text-xl uppercase">
                                    {{ substr($i->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col justify-center overflow-hidden">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-gray-900 truncate">{{ $i->name }}</h2>
                            <svg class="w-3 h-3 text-gray-400 transition-transform duration-200"
                                :class="openCardId === {{ $i->id }} ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        
                        {{-- BADGE ROLE SPATIE --}}
                        <div class="mt-1 flex flex-wrap gap-1">
                            @if ($i->roles->count() > 0)
                                @php $role = $i->roles->first(); @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $role->name == 'Super Admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $role->name == 'Manager' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $role->name == 'QC' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ in_array($role->name, ['KA Kupas', 'Kepala Produksi']) ? 'bg-red-100 text-red-800' : '' }}
                                    {{ in_array($role->name, ['Admin Gudang', 'Admin Kantor']) ? 'bg-green-100 text-green-800' : '' }}
                                    {{ !in_array($role->name, ['Super Admin', 'Manager', 'QC', 'KA Kupas', 'Kepala Produksi', 'Admin Gudang', 'Admin Kantor']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $role->name }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400 uppercase tracking-wider">
                                    No Role
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative">
                        <button
                            @click.stop="openDropdownId = (openDropdownId === {{ $i->id }} ? null : {{ $i->id }})"
                            @click.away="if(openDropdownId === {{ $i->id }}) openDropdownId = null"
                            class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition text-gray-500 focus:outline-none border border-transparent active:border-gray-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>

                        <div x-show="openDropdownId === {{ $i->id }}" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden outline-none">

                            <ul class="flex flex-col text-xs font-medium">
                                <li>
                                    <a href="{{ route('user.edit', $i->id) }}"
                                        class="w-full flex items-center gap-2 px-4 py-3 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition border-b border-gray-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit User
                                    </a>
                                </li>
                                <li>
                                    <form id="delete-form-{{ $i->id }}" action="{{ route('user.destroy', $i->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $i->id }})"
                                            class="w-full flex items-center gap-2 px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus User
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETAIL COLLAPSE --}}
            <div x-show="openCardId === {{ $i->id }}" x-collapse x-cloak
                class="mt-3 pt-3 border-t border-gray-100 space-y-3">
                <div class="grid grid-cols-1 gap-2">
                    <div class="flex justify-between items-center gap-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Username:</span>
                        <span class="text-xs text-gray-700 font-medium uppercase">{{ $i->username }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Perusahaan:</span>
                        <span class="text-xs text-gray-700 font-medium truncate">{{ $i->perusahaan->nama_perusahaan ?? 'All Akses' }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Kota:</span>
                        <span class="text-xs text-gray-700 font-medium truncate">{{ $i->perusahaan->kota ?? 'All Akses' }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Terdaftar:</span>
                        <span class="text-xs text-gray-700 font-medium">{{ $i->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Data user tidak ditemukan</p>
            <a href="{{ route('user.create') }}"
                class="mt-3 inline-block text-green-600 text-xs font-bold hover:underline uppercase">Tambah User Baru</a>
        </div>
    @endforelse
</div>

<div class="md:hidden mt-6">
    {{ $user->links('vendor.pagination.custom') }}
</div>