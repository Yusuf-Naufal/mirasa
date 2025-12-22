<x-layout.user.app>
    {{-- Mengubah space-y-2 menjadi space-y-8 agar jarak antar elemen besar lebih lega --}}
    <div class="space-y-8 p-4"> 
        
        {{-- Header Section --}}
        <div class="flex flex-col gap-4 md:justify-between mb-2"> {{-- Tambah mb-2 --}}

            <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center md:justify-between">
                <div class="flex gap-2">
                    <a href="{{ route('proses.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M11.5 7a4.5 4.5 0 1 1 0 9a4.5 4.5 0 0 1 0-9m0 2a.5.5 0 0 0-.5.5V11H9.5a.5.5 0 0 0 0 1H11v1.5a.5.5 0 0 0 1 0V12h1.5a.5.5 0 0 0 0-1H12V9.5a.5.5 0 0 0-.5-.5M7.258 8A5.48 5.48 0 0 0 6 11.5c0 .485.062.955.18 1.402A7 7 0 0 1 5 13c-1.175 0-2.27-.272-3.089-.77C1.091 11.73.5 10.965.5 10a2 2 0 0 1 2-2zM5 1.5A2.75 2.75 0 1 1 5 7a2.75 2.75 0 0 1 0-5.5m6.502.997a2.25 2.25 0 0 1 2.252 2.251a2.24 2.24 0 0 1-.586 1.51A5.5 5.5 0 0 0 11.5 6a5.5 5.5 0 0 0-1.667.257a2.252 2.252 0 0 1 1.669-3.76" />
                        </svg>
                        <span class="hidden md:block md:ml-2">Tambah Proses</span>
                    </a>

                    <button onclick="openModal('filterModal')"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M9 5a1 1 0 1 0 0 2a1 1 0 0 0 0-2M6.17 5a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-7.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 0 1 0-2zM15 11a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-2.83 0a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-1.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 1 1 0-2zM9 17a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-2.83 0a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-7.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 1 1 0-2z" />
                        </svg>
                        <span class="hidden md:block md:ml-2">Filter Proses</span>
                    </button>
                </div>

                <form action="" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#FFC829]"
                        placeholder="Cari...">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" {{-- Top dirubah ke 1/2 --}}
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>
        </div>

        {{-- CARD TABEL DAFTAR PROSES --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Daftar Proses Produksi
                </h2>
                <span class="bg-emerald-500 text-white text-xs px-2 py-1 rounded-md border border-emerald-400">
                    {{ count($daftarProses) }} Item
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-separate border-spacing-0">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-200">KODE</th>
                            <th class="px-6 py-4 border-b border-gray-200">PROSES</th>
                            <th class="px-6 py-4 border-b border-gray-200 text-center w-32">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarProses as $index => $p)
                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-emerald-700">{{ $p['kode'] }}</td>
                                <td class="px-6 py-4 font-medium text-gray-700 uppercase">{{ $p['proses'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('proses.edit', $index) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('proses.destroy', $index) }}" method="POST" onsubmit="return confirm('Hapus proses ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/50">
                                    Belum ada data proses produksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout.user.app>