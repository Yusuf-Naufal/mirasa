<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 1.2cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #2d3748;
            line-height: 1.5;
        }

        /* Kop Surat / Header */
        .header-container {
            border-bottom: 2px solid #4a5568;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a202c;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            font-size: 14pt;
            color: #4a5568;
            margin-top: 5px;
            font-weight: normal;
        }

        /* Info Periode */
        .info-section {
            margin-bottom: 25px;
        }

        .info-label {
            color: #718096;
            font-size: 9pt;
            text-transform: uppercase;
        }

        .info-value {
            font-weight: bold;
            font-size: 11pt;
        }

        /* Ringkasan Box */
        .summary-grid {
            width: 100%;
            margin-bottom: 30px;
        }

        .summary-card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 10px;
            width: 45%;
        }

        .summary-card.blue {
            border-left: 5px solid #3182ce;
        }

        .summary-card.orange {
            border-left: 5px solid #dd6b20;
        }

        .summary-label {
            font-size: 8pt;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-price {
            font-size: 14pt;
            font-weight: bold;
            color: #2d3748;
        }

        /* Styling Tabel */
        h3 {
            font-size: 11pt;
            text-transform: uppercase;
            color: #2d3748;
            border-left: 4px solid #4a5568;
            padding-left: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: #2d3748;
            color: white;
            text-align: left;
            padding: 12px 10px;
            font-size: 9pt;
            text-transform: uppercase;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .font-mono {
            font-family: 'Courier', monospace;
        }

        .unit-label {
            font-size: 8pt;
            color: #a0aec0;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 8pt;
            color: #a0aec0;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header-container">
        <h1 class="company-name">{{ $namaPerusahaan }}</h1>
        <div class="report-title">Laporan Analisis Produksi</div>
    </div>

    <table class="info-section">
        <tr>
            <td style="border:none; padding:0; width: 50%;">
                <div class="info-label">Periode Laporan</div>
                <div class="info-value">
                    @php
                        $dates = explode(' to ', $dateRange);
                        $start = \Carbon\Carbon::parse($dates[0])->translatedFormat('d F Y');
                        $end = isset($dates[1]) ? \Carbon\Carbon::parse($dates[1])->translatedFormat('d F Y') : $start;
                    @endphp

                    {{ $start === $end ? $start : $start . ' - ' . $end }}
                </div>
            </td>
            <td style="border:none; padding:0; text-align: right;">
                <div class="info-label">Status Dokumen</div>
                <div class="info-value" style="color: #38a169;">Finalized</div>
            </td>
        </tr>
    </table>

    <table class="summary-grid">
        <tr>
            <td style="border:none; padding:0;">
                <div class="summary-card blue">
                    <div class="summary-label">Total Biaya Bahan Baku</div>
                    <div class="summary-price">Rp {{ number_format($totalBiayaBB, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border:none; padding:0; width: 20px;"></td>
            <td style="border:none; padding:0;">
                <div class="summary-card orange">
                    <div class="summary-label">Biaya Bahan Penolong</div>
                    <div class="summary-price">Rp {{ number_format($totalBiayaBP, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <h3>I. Output: Hasil Produksi Jadi</h3>
    <table>
        <thead>
            <tr>
                <th width="50%">Deskripsi Produk</th>
                <th class="text-right">Kuantitas</th>
                <th class="text-right">Estimasi Nilai Asset</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilProduksi as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item['nama_barang'] }}</div>
                        <div class="unit-label">Satuan: {{ $item['satuan'] }}</div>
                    </td>
                    <td class="text-right font-mono">{{ number_format($item['total_qty'], 2) }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($item['total_nilai'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>II. Input: Rincian Bahan Keluar</h3>
    <table>
        <thead>
            <tr>
                <th width="50%">Nama Bahan Baku / Penolong</th>
                <th class="text-right">Kuantitas Pakai</th>
                <th class="text-right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangKeluar as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item['nama_barang'] }}</div>
                        <div class="unit-label">Satuan: {{ $item['satuan'] }}</div>
                    </td>
                    <td class="text-right font-mono">{{ number_format($item['total_qty'], 2) }}</td>
                    <td class="text-right font-mono" style="color: #3182ce;">Rp
                        {{ number_format($item['total_nilai'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated by System | Waktu Cetak: {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>

</html>
