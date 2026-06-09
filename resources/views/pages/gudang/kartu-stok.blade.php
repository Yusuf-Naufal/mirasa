<x-layout.beranda.app title="Kartu Stok">
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- Header Section --}}
            @can('inventory.show')
                <div class="mb-4 flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <a href="{{ route('inventory.show', $inventory->id) }}"
                            class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-all mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke daftar
                        </a>

                    </div>
                </div>
            @endcan

            <div class="flex flex-col md:flex-row md:items-start justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900">{{ $inventory->Barang->nama_barang }}</h1>
                    <p class="text-sm text-gray-500 font-medium">Perusahaan:
                        {{ $inventory->Perusahaan->nama_perusahaan }} ({{ $inventory->Perusahaan->kota }})</p>
                </div>

                <form action="{{ route('inventory.kartu-stok', $inventory->id) }}" method="GET"
                    class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black text-gray-400 uppercase mb-1">Bulan</label>
                        <select name="bulan"
                            class="text-sm border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black text-gray-400 uppercase mb-1">Tahun</label>
                        <select name="tahun"
                            class="text-sm border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            @foreach (range(now()->year - 3, now()->year + 1) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="submit"
                            class="px-6 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-blue-600 transition-all">
                            TAMPILKAN
                        </button>

                        {{-- TOMBOL PDF BARU --}}
                        @can('inventory.cetak-kartu-stok')
                            <a href="{{ route('inventory.kartu-stok.pdf', [$inventory->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                                class="px-6 py-2.5 bg-white text-red-600 border border-red-200 text-xs font-bold rounded-xl hover:bg-red-50 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF
                            </a>
                        @endcan
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Saldo Awal</p>
                    <p class="text-2xl font-black text-gray-800">{{ number_format($saldoAwalValue, 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm border-l-4 border-l-emerald-500">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Masuk</p>
                    <p class="text-2xl font-black text-emerald-600">
                        +{{ number_format($mutasi->where('qty', '>', 0)->sum('qty'), 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm border-l-4 border-l-red-500">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Keluar</p>
                    <p class="text-2xl font-black text-red-600">
                        -{{ number_format(abs($mutasi->where('qty', '<', 0)->sum('qty')), 2, ',', '.') }}</p>
                </div>
                <div class="bg-blue-600 p-6 rounded-3xl shadow-lg shadow-blue-200">
                    <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1">Saldo Akhir</p>
                    <p class="text-2xl font-black text-white">
                        {{ number_format($mutasi->last() ? $mutasi->last()->saldo_qty : $saldoAwalValue, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="2"
                                class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest align-middle border-b border-gray-200">
                                Tanggal
                            </th>
                            <th rowspan="2"
                                class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest align-middle border-b border-gray-200 min-w-[200px]">
                                Keterangan / Batch
                            </th>
                            <th colspan="3"
                                class="px-4 py-3 text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center border-l border-b border-gray-200 bg-emerald-50/50">
                                Barang Masuk (IN)
                            </th>
                            <th colspan="3"
                                class="px-4 py-3 text-[10px] font-black text-red-600 uppercase tracking-widest text-center border-l border-b border-gray-200 bg-red-50/50">
                                Barang Keluar (OUT)
                            </th>
                            <th colspan="3"
                                class="px-4 py-3 text-[10px] font-black text-blue-600 uppercase tracking-widest text-center border-l border-b border-gray-200 bg-blue-50/50">
                                Saldo Berjalan
                            </th>
                        </tr>
                        <tr>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-l border-b border-gray-200 bg-emerald-50/30">
                                Qty</th>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-b border-gray-200 bg-emerald-50/30">
                                Harga</th>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-b border-gray-200 bg-emerald-50/30">
                                Total</th>

                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-l border-b border-gray-200 bg-red-50/30">
                                Qty</th>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-b border-gray-200 bg-red-50/30">
                                Harga</th>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-b border-gray-200 bg-red-50/30">
                                Total</th>

                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-l border-b border-gray-200 bg-blue-50/30">
                                Qty</th>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-b border-gray-200 bg-blue-50/30">
                                Harga Valuasi</th>
                            <th
                                class="px-3 py-2 text-[10px] font-bold text-gray-500 uppercase text-right border-b border-gray-200 bg-blue-50/30">
                                Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @php
                            // 1. Pastikan nilai tidak NULL menggunakan (float) dan fallback ?? 0
                            $saldoAwalValue = (float) ($saldoAwalValue ?? 0);
                            $runningNilai = (float) ($saldoAwalNilai ?? 0);

                            // 2. Hitung harga rata-rata awal
                            $hargaRataAwal = $saldoAwalValue > 0 ? $runningNilai / $saldoAwalValue : 0;

                            $sumQtyMasuk = 0;
                            $sumTotalMasuk = 0;
                            $sumQtyKeluar = 0;
                            $sumTotalKeluar = 0;
                        @endphp

                        <tr class="bg-gray-50/30 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-xs font-bold text-gray-400">
                                01/{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}
                            </td>
                            <td class="px-4 py-3 text-xs font-black text-gray-500 italic uppercase">
                                SALDO AWAL BULAN INI
                            </td>

                            <td class="px-3 py-3 text-right text-gray-300 border-l border-gray-100">-</td>
                            <td class="px-3 py-3 text-right text-gray-300">-</td>
                            <td class="px-3 py-3 text-right text-gray-300">-</td>
                            <td class="px-3 py-3 text-right text-gray-300 border-l border-gray-100">-</td>
                            <td class="px-3 py-3 text-right text-gray-300">-</td>
                            <td class="px-3 py-3 text-right text-gray-300">-</td>

                            <td
                                class="px-3 py-3 text-sm font-black text-right text-blue-600 border-l border-gray-100 bg-blue-50/10">
                                {{ number_format($saldoAwalValue, 2, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-xs text-right font-medium text-gray-400 bg-blue-50/10">
                                {{ number_format($hargaRataAwal, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-xs text-right font-bold text-gray-600 bg-blue-50/10">
                                {{ number_format($runningNilai, 0, ',', '.') }}
                            </td>
                        </tr>

                        @forelse ($mutasi as $log)
                            @php
                                $isMasuk = $log->qty > 0;
                                $isKeluar = $log->qty < 0;
                                $qtyAbs = abs($log->qty);
                                $harga = $log->source?->harga ?? 0;
                                $totalTransaksi = $qtyAbs * $harga;

                                // --- PERBAIKAN LOGIKA VALUASI (MOVING AVERAGE) ---
                                if ($isMasuk) {
                                    $sumQtyMasuk += $qtyAbs;
                                    $sumTotalMasuk += $totalTransaksi;

                                    // Uang Persediaan Bertambah
                                    $runningNilai += $totalTransaksi;
                                } elseif ($isKeluar) {
                                    $sumQtyKeluar += $qtyAbs;
                                    $sumTotalKeluar += $totalTransaksi;

                                    // Uang Persediaan Berkurang
                                    $runningNilai -= $totalTransaksi;
                                }

                                // Mencegah minus kecil akibat presisi float (misal -0.0001) saat stok habis
                                if ($runningNilai < 0.1 && $log->saldo_qty <= 0) {
                                    $runningNilai = 0;
                                }

                                // Harga Valuasi = Rata-rata dari Total Nilai / Qty Saldo Saat Ini
                                $hargaValuasi = $log->saldo_qty > 0 ? $runningNilai / $log->saldo_qty : 0;
                                // --------------------------------------------------
                            @endphp

                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-4 py-3 text-xs font-medium text-gray-600">
                                    {{ \Carbon\Carbon::parse($log->tanggal_transaksi)->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($log->nomor_batch)
                                        <p class="text-sm font-bold text-gray-800">{{ $log->nomor_batch }}</p>
                                        <span
                                            class="text-[9px] font-black bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded uppercase mt-0.5 inline-block">{{ $log->keterangan }}</span>
                                    @else
                                        <p class="text-xs font-bold text-gray-800 uppercase">
                                            {{ $isMasuk ? 'Barang Masuk' : $log->keterangan }}
                                        </p>
                                    @endif
                                </td>

                                <td
                                    class="px-3 py-3 text-sm text-right font-bold border-l border-gray-100 {{ $isMasuk ? 'text-emerald-600 bg-emerald-50/10' : 'text-gray-300' }}">
                                    {{ $isMasuk ? number_format($qtyAbs, 2, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="px-3 py-3 text-xs text-right {{ $isMasuk ? 'text-gray-500 bg-emerald-50/10' : 'text-gray-300' }}">
                                    {{ $isMasuk && $harga > 0 ? number_format($harga, 0, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="px-3 py-3 text-xs text-right font-black {{ $isMasuk ? 'text-gray-800 bg-emerald-50/10' : 'text-gray-300' }}">
                                    {{ $isMasuk && $totalTransaksi > 0 ? number_format($totalTransaksi, 0, ',', '.') : '-' }}
                                </td>

                                <td
                                    class="px-3 py-3 text-sm text-right font-bold border-l border-gray-100 {{ $isKeluar ? 'text-red-600 bg-red-50/10' : 'text-gray-300' }}">
                                    {{ $isKeluar ? number_format($qtyAbs, 2, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="px-3 py-3 text-xs text-right {{ $isKeluar ? 'text-gray-500 bg-red-50/10' : 'text-gray-300' }}">
                                    {{ $isKeluar && $harga > 0 ? number_format($harga, 0, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="px-3 py-3 text-xs text-right font-black {{ $isKeluar ? 'text-gray-800 bg-red-50/10' : 'text-gray-300' }}">
                                    {{ $isKeluar && $totalTransaksi > 0 ? number_format($totalTransaksi, 0, ',', '.') : '-' }}
                                </td>

                                <td
                                    class="px-3 py-3 text-sm text-right font-black text-blue-700 border-l border-blue-50 bg-blue-50/20">
                                    {{ number_format($log->saldo_qty, 2, ',', '.') }}
                                </td>
                                <td
                                    class="px-3 py-3 text-xs text-right font-medium text-gray-400 bg-blue-50/10 group-hover:text-gray-500 transition-colors">
                                    {{-- Menggunakan Variabel $hargaValuasi hasil pembagian --}}
                                    {{ $hargaValuasi > 0 ? number_format($hargaValuasi, 0, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="px-3 py-3 text-xs text-right font-bold text-gray-600 bg-blue-50/10 group-hover:text-gray-800 transition-colors">
                                    {{-- Menggunakan Variabel $runningNilai --}}
                                    {{ $runningNilai > 0 ? number_format($runningNilai, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm font-bold text-gray-400 italic">Tidak ada transaksi mutasi
                                            pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                    <tfoot class="bg-gray-100 font-bold uppercase border-t-4 border-gray-200">
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-right text-xs text-gray-600 tracking-wider">
                                TOTAL MUTASI BULAN INI
                            </td>

                            <td class="px-3 py-4 text-sm text-right text-emerald-600 border-l border-gray-200">
                                {{ number_format($sumQtyMasuk, 2, ',', '.') }}
                            </td>
                            <td class="px-3 py-4 text-center text-gray-400">-</td>
                            <td class="px-3 py-4 text-sm text-right text-emerald-700">
                                {{ number_format($sumTotalMasuk, 0, ',', '.') }}
                            </td>

                            <td class="px-3 py-4 text-sm text-right text-red-600 border-l border-gray-200">
                                {{ number_format($sumQtyKeluar, 2, ',', '.') }}
                            </td>
                            <td class="px-3 py-4 text-center text-gray-400">-</td>
                            <td class="px-3 py-4 text-sm text-right text-red-700">
                                {{ number_format($sumTotalKeluar, 0, ',', '.') }}
                            </td>

                            <td colspan="3"
                                class="px-3 py-4 text-center text-gray-400 border-l border-gray-200 bg-gray-50/50">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</x-layout.beranda.app>
