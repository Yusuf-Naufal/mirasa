<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 1.2cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #334155;
        }

        .header {
            border-bottom: 2px solid #1e293b;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .company {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* Analysis Section */
        .grid {
            width: 100%;
            margin-bottom: 30px;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
        }

        .label {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .value {
            font-size: 14pt;
            font-weight: bold;
            color: #1e293b;
        }

        .status {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 5px;
        }

        .up {
            color: #ef4444;
        }

        /* Merah jika pengeluaran naik */
        .down {
            color: #10b981;
        }

        /* Hijau jika pengeluaran turun */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #1e293b;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 9pt;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8pt;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company">{{ $namaPerusahaan }}</div>
        <div style="font-size: 12pt;">Laporan Analisis Pengeluaran</div>
        <div style="color: #64748b;">
            Periode:
            {{ $filterType === 'month' ? \Carbon\Carbon::create(null, $selectedMonth)->translatedFormat('F') : '' }}
            {{ $selectedYear }}
        </div>
    </div>

    <table class="grid">
        <tr>
            <td style="border:none; padding:0; width: 48%;">
                <div class="card">
                    <div class="label">Total Pengeluaran Periode Ini</div>
                    <div class="value">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</div>
                    <div class="status {{ $diff > 0 ? 'up' : 'down' }}">
                        @if ($diff > 0)
                            {{-- Icon Panah Naik (Merah) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 15 15">
                                <path fill="currentColor" d="m7.5 3l7.5 8H0z" />
                            </svg>
                            <span>Naik</span>
                        @else
                            {{-- Icon Panah Turun (Hijau) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 15 15">
                                <path fill="currentColor" d="M7.5 12L0 4h15z" />
                            </svg>
                            <span>Turun</span>
                        @endif

                        Rp {{ number_format(abs($diff), 0, ',', '.') }} ({{ number_format($percentage, 1) }}%)
                    </div>
                </div>
            </td>
            <td style="border:none; width: 4%;"></td>
            <td style="border:none; padding:0; width: 48%;">
                <div class="card">
                    <div class="label">Total Pengeluaran Periode Lalu</div>
                    <div class="value">Rp {{ number_format($totalBulanLalu, 0, ',', '.') }}</div>
                    <div style="font-size: 8pt; color: #94a3b8; margin-top: 5px;">
                        Perbandingan dengan {{ $filterType === 'month' ? 'Bulan' : 'Tahun' }} Sebelumnya
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <h3>Ringkasan Per Kategori</h3>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Total Pengeluaran</th>
                <th class="text-right">Kontribusi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chartData as $kat => $total)
                <tr>
                    <td><strong>{{ $kat }}</strong></td>
                    <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format(($total / $totalBulanIni) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="page-break-before: always;"></div>

    <h3>Rincian Transaksi Pengeluaran (Per Kategori)</h3>

    @php
        // Mengelompokkan data berdasarkan kategori
        $groupedData = $dataRincian->groupBy('kategori');
    @endphp

    @foreach ($groupedData as $kategori => $items)
        <div style="margin-bottom: 20px;">
            <div
                style="background-color: #f1f5f9; padding: 8px 12px; border-left: 4px solid #1e293b; font-weight: bold; text-transform: uppercase; font-size: 9pt;">
                Kategori: {{ $kategori }}
                <span style="float: right; color: #64748b;">Total: Rp
                    {{ number_format($items->sum('jumlah_pengeluaran'), 0, ',', '.') }}</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="20%">Tanggal</th>
                        <th width="55%">Sub Kategori</th>
                        <th width="55%">Keterangan</th>
                        <th width="25%" class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->translatedFormat('d') }}</td>
                            <td>{{ $item->sub_kategori ?? '-' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td class="text-right">Rp {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    @if ($groupedData->isEmpty())
        <div style="text-align: center; padding: 20px; border: 1px dashed #e2e8f0; color: #94a3b8;">
            Tidak ada data pengeluaran untuk periode ini.
        </div>
    @endif

    <div class="footer">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</div>
</body>

</html>
