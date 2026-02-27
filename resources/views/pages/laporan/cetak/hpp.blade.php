<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 1.2cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 9pt;
            color: #334155;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .company {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* HPP Highlight Box */
        .hpp-hero {
            background: #1e293b;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .hpp-label {
            font-size: 8pt;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .hpp-value {
            font-size: 20pt;
            font-weight: bold;
        }

        /* Grid Layout */
        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 6px;
        }

        .card-label {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
        }

        .card-value {
            font-size: 11pt;
            font-weight: bold;
            color: #1e293b;
        }

        .status-up {
            color: #ef4444;
        }

        /* Merah: Biaya Naik */
        .status-down {
            color: #10b981;
        }

        /* Hijau: Biaya Turun/Efisien */

        h3 {
            font-size: 10pt;
            text-transform: uppercase;
            border-left: 4px solid #1e293b;
            padding-left: 8px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #f1f5f9;
            color: #475569;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8pt;
        }

        td {
            padding: 8px;
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

        .total-row {
            background: #f8fafc;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company">{{ $namaPerusahaan }}</div>
        <div style="font-size: 12pt;">Laporan Analisis Harga Pokok Produksi (HPP)</div>
        <div style="color: #64748b;">
            Periode:
            {{ $filterType === 'month' ? \Carbon\Carbon::create(null, $selectedMonth)->translatedFormat('F') : '' }}
            {{ $selectedYear }}
        </div>
    </div>

    {{-- HIGHLIGHT HPP --}}
    <div class="hpp-hero">
        <table style="width: 100%; border:none;">
            <tr>
                <td style="border:none; padding:0;">
                    <div class="hpp-label">Estimasi HPP per Kg</div>
                    <div class="hpp-value">Rp {{ number_format($hppPerKg, 2, ',', '.') }}</div>
                </td>
                <td style="border:none; padding:0; text-align: right;">
                    <div class="hpp-label">Tren vs Periode Lalu</div>
                    <div style="font-size: 14pt; font-weight: bold;">
                        @if ($diffHppPct > 0)
                            {{-- Teks untuk Kenaikan Biaya --}}
                            <span style="color: #feb2b2; font-size: 10pt; text-transform: uppercase;">NAIK</span>
                            <span
                                style="color: #feb2b2; margin-left: 5px;">{{ number_format(abs($diffHppPct), 1) }}%</span>
                        @elseif ($diffHppPct < 0)
                            {{-- Teks untuk Penurunan Biaya --}}
                            <span style="color: #9ae6b4; font-size: 10pt; text-transform: uppercase;">TURUN</span>
                            <span
                                style="color: #9ae6b4; margin-left: 5px;">{{ number_format(abs($diffHppPct), 1) }}%</span>
                        @else
                            {{-- Teks untuk Biaya Stabil --}}
                            <span style="color: #cbd5e0; font-size: 10pt; text-transform: uppercase;">STABIL</span>
                            <span style="color: #cbd5e0; margin-left: 5px;">0%</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- RINGKASAN BIAYA --}}
    <table class="grid">
        <tr>
            <td style="border:none; padding:0; width: 32%;">
                <div class="card">
                    <div class="card-label">Total Biaya (Input)</div>
                    <div class="card-value">Rp {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border:none; width: 2%;"></td>
            <td style="border:none; padding:0; width: 32%;">
                <div class="card">
                    <div class="card-label">Volume Hasil (Output)</div>
                    <div class="card-value">{{ number_format($totalVolumeProduksi, 2, ',', '.') }} Kg</div>
                </div>
            </td>
            <td style="border:none; width: 2%;"></td>
            <td style="border:none; padding:0; width: 32%;">
                <div class="card">
                    <div class="card-label">Jumlah SKU Aktif</div>
                    <div class="card-value">{{ $summary['current_count_sku'] }} Item</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- KOMPONEN BIAYA --}}
    <h3>I. Rincian Komponen Biaya (Input)</h3>
    <table>
        <thead>
            <tr>
                <th>Deskripsi Komponen</th>
                <th class="text-right">Nominal Biaya</th>
                <th class="text-right">Kontribusi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pemakaian Bahan Baku</td>
                <td class="text-right">Rp {{ number_format($summaryBahan['total_harga_baku'], 0, ',', '.') }}</td>
                <td class="text-right">
                    {{ number_format(($summaryBahan['total_harga_baku'] / $grandTotalBiayaHpp) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td>Pemakaian Bahan Penolong</td>
                <td class="text-right">Rp {{ number_format($summaryBahan['total_harga_penolong'], 0, ',', '.') }}</td>
                <td class="text-right">
                    {{ number_format(($summaryBahan['total_harga_penolong'] / $grandTotalBiayaHpp) * 100, 1) }}%</td>
            </tr>
            @foreach ($bebanKategoriHpp as $beban)
                <tr>
                    <td>Beban: {{ $beban['nama'] }}</td>
                    <td class="text-right">Rp {{ number_format($beban['total'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($beban['persen'], 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL BIAYA PRODUKSI</td>
                <td class="text-right">Rp {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}</td>
                <td class="text-right">100%</td>
            </tr>
        </tfoot>
    </table>

    <div style="page-break-before: always;"></div>

    {{-- DETAIL OUTPUT --}}
    <h3>II. Rincian Hasil Produksi (Output)</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th class="text-right">Qty (Unit)</th>
                <th class="text-right">Berat (Kg)</th>
                <th class="text-right">Total Biaya Asset</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rincianProduksi as $item)
                <tr>
                    <td><strong>{{ $item['nama_barang'] }}</strong><br><small>{{ $item['tipe'] }}</small></td>
                    <td class="text-right">{{ number_format($item['total_diterima'], 2) }} {{ $item['satuan'] }}</td>
                    <td class="text-right">{{ number_format($item['total_qty_kg'], 2) }} Kg</td>
                    <td class="text-right">Rp {{ number_format($item['total_biaya'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} | Analisis Laporan HPP
    </div>
</body>

</html>
