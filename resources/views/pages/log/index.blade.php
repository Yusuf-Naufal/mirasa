<x-layout.user.app title="Log Activity">
    <div class="space-y-6">
        {{-- HEADER & SEARCH SECTION --}}
        <div class="bg-white p-5 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-50 pb-5">
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight italic uppercase italic">
                        Log Aktivitas Sistem
                    </h1>
                    <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">
                        Monitoring riwayat perubahan data secara real-time
                    </p>
                </div>
                {{-- Status Badge (Opsional) --}}
                <div class="hidden md:block">
                    <span
                        class="bg-blue-50 text-blue-600 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-tighter">
                        Sistem Aktif
                    </span>
                </div>
            </div>

            {{-- FORM FILTER --}}
            <form action="{{ request()->url() }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">

                    {{-- Search - Memakan 2 kolom di HP & Desktop agar lebih luas --}}
                    <div class="col-span-2 relative">
                        <label
                            class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block ml-1 tracking-wider">Pencarian</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari user atau aktivitas..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-gray-50 border-gray-100 text-xs font-semibold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-gray-400">
                        </div>
                    </div>

                    {{-- Perusahaan - Tampil Jika Super Admin --}}
                    @if (auth()->user()->hasRole('Super Admin'))
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block ml-1 tracking-wider">Perusahaan</label>
                            <select name="id_perusahaan"
                                class="w-full py-2.5 rounded-2xl bg-gray-50 border-gray-100 text-xs font-semibold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                                <option value="">Semua Perusahaan</option>
                                @foreach ($listPerusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Start Date --}}
                    <div class="col-span-1">
                        <label
                            class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block ml-1 tracking-wider">Dari
                            Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-4 py-2.5 rounded-2xl bg-gray-50 border-gray-100 text-xs font-semibold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                    </div>

                    {{-- End Date --}}
                    <div class="col-span-1">
                        <label
                            class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block ml-1 tracking-wider">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-4 py-2.5 rounded-2xl bg-gray-50 border-gray-100 text-xs font-semibold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                    </div>

                    {{-- Action --}}
                    <div class="col-span-1">
                        <label
                            class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block ml-1 tracking-wider">Aksi</label>
                        <select name="action"
                            class="w-full py-2.5 rounded-2xl bg-gray-50 border-gray-100 text-xs font-semibold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                            <option value="">Semua</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created
                            </option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated
                            </option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted
                            </option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-span-2 md:col-span-3 lg:col-span-1 flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-tighter hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                        <a href="{{ request()->url() }}" title="Reset Filter"
                            class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 hover:text-gray-700 active:scale-95 transition-all flex items-center justify-center border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABEL LOG --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[800px]">
                    <thead class="bg-gray-50 uppercase text-[10px] font-black text-gray-500">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Perusahaan</th>
                            <th class="px-6 py-4">Pelaku (User)</th>
                            <th class="px-6 py-4">Aktivitas</th>
                            <th class="px-6 py-4">Model/Data</th>
                            <th class="px-6 py-4">Detail Perubahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="font-bold text-gray-900">{{ $log->created_at->format('d/m/Y') }}</span>
                                    <span
                                        class="block text-[10px] text-gray-400 font-medium">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                {{-- Kolom Perusahaan Baru --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-black text-blue-600 uppercase tracking-tighter italic">
                                            {{ $log->causer->perusahaan->nama_perusahaan ?? 'SYSTEM' }}
                                        </span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase">
                                            {{ $log->causer->perusahaan->kota ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-[10px]">
                                            {{ substr($log->causer->name ?? 'SYS', 0, 2) }}
                                        </div>
                                        <div>
                                            <span
                                                class="font-bold text-gray-700 block">{{ $log->causer->name ?? 'System' }}</span>
                                            <span
                                                class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">{{ $log->causer->roles->first()->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeColor =
                                            [
                                                'created' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'updated' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'deleted' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            ][$log->description] ?? 'bg-blue-50 text-blue-600 border-blue-100';
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full border text-[9px] font-black uppercase tracking-widest {{ $badgeColor }}">
                                        {{ $log->description }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-600 italic uppercase tracking-tighter">
                                        {{ class_basename($log->subject_type) }}
                                    </span>
                                    <span class="block text-[10px] text-gray-400">ID: #{{ $log->subject_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->changes)
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="text-blue-500 hover:text-blue-700 font-bold flex items-center gap-1 uppercase text-[9px]">
                                                <svg class="w-3 h-3" :class="open ? 'rotate-180' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Lihat Data
                                            </button>

                                            <div x-show="open" x-collapse
                                                class="mt-2 bg-gray-900 text-gray-300 p-3 rounded-xl text-[10px] font-mono leading-relaxed max-w-xs overflow-x-auto">
                                                @if (isset($log->changes['old']))
                                                    <p class="text-rose-400 font-bold mb-1">// Data Lama</p>
                                                    @foreach ($log->changes['old'] as $key => $val)
                                                        <div class="ml-2 italic">{{ $key }}: <span
                                                                class="text-white">"{{ $val }}"</span></div>
                                                    @endforeach
                                                    <hr class="my-2 border-white/10">
                                                @endif

                                                @if (isset($log->changes['attributes']))
                                                    <p class="text-emerald-400 font-bold mb-1">// Data Baru</p>
                                                    @foreach ($log->changes['attributes'] as $key => $val)
                                                        <div class="ml-2 italic">{{ $key }}: <span
                                                                class="text-white">"{{ $val }}"</span></div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada rincian</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                                            <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Belum ada
                                            aktivitas tercatat</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                {{ $logs->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</x-layout.user.app>
