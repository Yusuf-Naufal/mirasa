@props(['supplier'])

<div x-data="{ openDropdownId: null }" class="space-y-4 md:hidden">
    @forelse ($supplier as $index => $i)
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        {{-- Gunakan null coalescing ?? agar tidak error jika field kosong --}}
                        <h2 class="text-sm font-bold text-gray-900">{{ $i['nama'] ?? 'Tanpa Nama' }}</h2>
                        <p class="text-xs text-gray-400">
                            {{ $i['kode'] ?? '-' }} | {{ $i['kategori'] ?? 'Umum' }}
                        </p>
                    </div>
                </div>
                
                <div class="relative">
                    {{-- Alpine.js toggle dropdown --}}
                    <button @click="openDropdownId = (openDropdownId === {{ $index }} ? null : {{ $index }})" 
                            class="p-2 text-gray-400 hover:bg-gray-100 rounded-full">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>

                    <div x-show="openDropdownId === {{ $index }}" 
                         @click.away="openDropdownId = null" 
                         x-transition
                         class="absolute right-0 mt-2 w-32 bg-white border border-gray-100 rounded-lg shadow-xl z-20">
                        <a href="{{ route('supplier.edit', $index) }}" 
                           class="block px-4 py-2 text-xs text-gray-700 hover:bg-amber-50">Edit</a>
                        
                        <form action="{{ route('supplier.destroy', $index) }}" method="POST" 
                              onsubmit="return confirm('Hapus supplier ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl py-10 text-center">
            <p class="text-gray-500">Data tidak ditemukan</p>
        </div>
    @endforelse
</div>