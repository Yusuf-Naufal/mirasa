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
            margin-bottom: 15px;
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

        /* Formula Box */
        .formula-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
        }

        .formula-title {
            font-size: 8pt;
            color: #475569;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .formula-desc {
            font-size: 8pt;
            color: #64748b;
            margin-bottom: 12px;
        }

        .formula-fraction {
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            margin: 0 10px;
        }

        .formula-numerator {
            border-bottom: 1px solid #1e293b;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-weight: bold;
            color: #1e293b;
            font-size: 9pt;
        }

        .formula-denominator {
            font-weight: bold;
            color: #1e293b;
        }

        .formula-result {
            display: inline-block;
            vertical-align: middle;
            font-size: 13pt;
            font-weight: bold;
            color: #dc2626;
            background: #fee2e2;
            padding: 5px 12px;
            border-radius: 6px;
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

        h3 {
            font-size: 10pt;
            text-transform: uppercase;
            border-left: 4px solid #1e293b;
            padding-left: 8px;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        h4 {
            font-size: 9pt;
            color: #475569;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
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
        <table style="width: 100%; border:none; margin-bottom: 0;">
            <tr>
                <td style="border:none; padding:0;">
                    <div class="hpp-label">Harga Pokok Produksi (HPP) per Kg</div>
                    <div class="hpp-value">Rp {{ number_format($hppPerKg, 2, ',', '.') }}</div>
                </td>
                <td style="border:none; padding:0; text-align: right;">
                    <div class="hpp-label">Tren vs Periode Lalu</div>
                    <div style="font-size: 14pt; font-weight: bold;">
                        @if ($diffHppPct > 0)
                            <span style="color: #feb2b2; font-size: 10pt; text-transform: uppercase;">NAIK</span>
                            <span
                                style="color: #feb2b2; margin-left: 5px;">{{ number_format(abs($diffHppPct), 1) }}%</span>
                        @elseif ($diffHppPct < 0)
                            <span style="color: #9ae6b4; font-size: 10pt; text-transform: uppercase;">TURUN</span>
                            <span
                                style="color: #9ae6b4; margin-left: 5px;">{{ number_format(abs($diffHppPct), 1) }}%</span>
                        @else
                            <span style="color: #cbd5e0; font-size: 10pt; text-transform: uppercase;">STABIL</span>
                            <span style="color: #cbd5e0; margin-left: 5px;">0%</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- KOTAK RUMUS PERHITUNGAN HPP LENGKAP --}}
    <div class="formula-box">
        <div class="formula-title">Penjabaran Rumus HPP</div>
        <div class="formula-desc">
            (Total Pemakaian Bahan Baku + Total Bahan Penolong + Total Pengeluaran Operasional) / Total Volume
            Hasil Produksi
        </div>
        <div>
            <div class="formula-fraction">
                <div class="formula-numerator">
                    (Rp {{ number_format($summaryBahan['total_harga_baku'], 0, ',', '.') }}
                    + Rp {{ number_format($summaryBahan['total_harga_penolong'], 0, ',', '.') }}
                    + Rp {{ number_format($totalBebanHpp, 0, ',', '.') }})
                    = Rp {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}
                </div>
                <div class="formula-denominator">
                    {{ number_format($totalVolumeProduksi, 2, ',', '.') }} Kg
                </div>
            </div>
            <div
                style="display: inline-block; vertical-align: middle; margin: 0 10px; font-weight: bold; font-size: 14pt;">
                =</div>
            <div class="formula-result">
                Rp {{ number_format($hppPerKg, 2, ',', '.') }} / Kg
            </div>
        </div>
    </div>

    {{-- RINGKASAN DATA --}}
    <table class="grid">
        <tr>
            <td style="border:none; padding:0; width: 32%;">
                <div class="card">
                    <div class="card-label">Total Biaya Produksi</div>
                    <div class="card-value">Rp {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border:none; width: 2%;"></td>
            <td style="border:none; padding:0; width: 32%;">
                <div class="card">
                    <div class="card-label">Volume Hasil Jadi</div>
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

    {{-- BAGIAN 1: PEMAKAIAN BAHAN --}}
    <h3>I. Biaya Pemakaian Bahan</h3>
    <table>
        <thead>
            <tr>
                <th>Kategori Bahan</th>
                <th class="text-right">Total Biaya</th>
                <th class="text-right">Persentase dari Total HPP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pemakaian Bahan Baku</td>
                <td class="text-right">Rp {{ number_format($summaryBahan['total_harga_baku'], 0, ',', '.') }}</td>
                <td class="text-right">
                    {{ $grandTotalBiayaHpp > 0 ? number_format(($summaryBahan['total_harga_baku'] / $grandTotalBiayaHpp) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr>
                <td>Pemakaian Bahan Penolong</td>
                <td class="text-right">Rp {{ number_format($summaryBahan['total_harga_penolong'], 0, ',', '.') }}</td>
                <td class="text-right">
                    {{ $grandTotalBiayaHpp > 0 ? number_format(($summaryBahan['total_harga_penolong'] / $grandTotalBiayaHpp) * 100, 1) : 0 }}%
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>SUBTOTAL BIAYA BAHAN</td>
                <td class="text-right">Rp {{ number_format($summaryBahan['total_harga_keluar'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
            </tr>
        </tfoot>
    </table>

    {{-- BAGIAN 2: PENGELUARAN OPERASIONAL --}}
    <h3>II. Biaya Pengeluaran Operasional (Beban HPP)</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori Pengeluaran</th>
                <th>Keterangan</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengeluaranHpp as $beban)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($beban->tanggal_pengeluaran)->translatedFormat('d M Y') }}</td>
                    <td><span
                            style="padding: 2px 6px; background: #e2e8f0; border-radius: 4px; font-size: 7pt;">{{ $beban->kategori }}</span>
                    </td>
                    <td>{{ $beban->keterangan ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($beban->jumlah_pengeluaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8;">Tidak ada catatan pengeluaran
                        operasional.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">SUBTOTAL PENGELUARAN OPERASIONAL</td>
                <td class="text-right">Rp {{ number_format($totalBebanHpp, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table style="margin-top: 15px;">
        <tr style="background-color: #0f172a; color: white;">
            <td style="padding: 10px; font-weight: bold; font-size: 10pt;">TOTAL BIAYA PRODUKSI KESELURUHAN (I + II)
            </td>
            <td style="padding: 10px; font-weight: bold; font-size: 10pt;" class="text-right">Rp
                {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div style="page-break-before: always;"></div>

    {{-- BAGIAN 3: DETAIL OUTPUT PRODUKSI --}}
    <h3>III. Rincian Hasil Produksi (Output)</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk / SKU</th>
                <th class="text-right">Qty (Unit)</th>
                <th class="text-right">Berat (Kg)</th>
                <th class="text-right">Total Alokasi Biaya Asset</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rincianProduksi as $item)
                <tr>
                    <td><strong>{{ $item['nama_barang'] }}</strong><br><small style="color: #64748b;">Tipe:
                            {{ $item['tipe'] }}</small></td>
                    <td class="text-right">{{ number_format($item['total_diterima'], 2) }} {{ $item['satuan'] }}</td>
                    <td class="text-right">{{ number_format($item['total_qty_kg'], 2) }} Kg</td>
                    <td class="text-right">Rp {{ number_format($item['total_biaya'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row" style="background-color: #f1f5f9;">
                <td colspan="2" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right" style="color: #0f172a;">{{ number_format($totalVolumeProduksi, 2, ',', '.') }}
                    Kg</td>
                <td class="text-right" style="color: #0f172a;">Rp {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem | {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>

</html>
