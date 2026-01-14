@props(['gas'])

<div class="hidden md:block bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse min-w-[600px]">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-left">
                    <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                        Waktu & Perusahaan</th>
                    <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                        Pemasok (Supplier)</th>
                    <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">
                        Volume</th>
                    <th class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">
                        Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($gas as $log)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span
                                    class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($log->tanggal_pemakaian)->translatedFormat('d F Y') }}</span>
                                @if (auth()->user()->hasRole('Super Admin'))
                                    <span
                                        class="text-[10px] text-blue-500 font-semibold uppercase tracking-tighter mt-0.5">🏢
                                        {{ $log->perusahaan->nama_perusahaan ?? '-' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-left">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs shadow-sm">
                                    {{ substr($log->supplier->nama_supplier, 0, 1) }}
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-700">{{ $log->supplier->nama_supplier }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex flex-col items-end">
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-sm font-black text-gray-900">{{ number_format($log->jumlah_gas, 2, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">m³</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <button type="button" onclick="openModal('editModal-{{ $log->id }}')"
                                    class="p-2 text-blue-400 hover:text-blue-600 hover:bg-blue-100 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <form action="{{ route('gas.destroy', $log->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data pemakaian ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-gray-300 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>


                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-200 font-bold">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h3 class="text-gray-900 font-bold">Data Kosong</h3>
                                <p class="text-gray-400 text-sm mt-1">Belum ada catatan pemakaian gas yang
                                    ditemukan.</p>
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
            {{ $gas->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
