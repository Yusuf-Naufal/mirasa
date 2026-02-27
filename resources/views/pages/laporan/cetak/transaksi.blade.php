<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 9pt;
            color: #2d3748;
        }

        .header {
            border-bottom: 2px solid #2d3748;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        /* Section Styling */
        .section-title {
            background: #edf2f7;
            padding: 8px 12px;
            font-weight: bold;
            border-left: 4px solid #2d3748;
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .entity-header {
            background: #f7fafc;
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            font-weight: bold;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            background-color: #f8fafc;
            color: #4a5568;
            padding: 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
            font-size: 8pt;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8pt;
            color: #a0aec0;
            text-align: right;
        }

        .summary-row {
            background-color: #fffaf0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">{{ $namaPerusahaan }}</h1>
        <div style="font-size: 12pt;">Laporan Ringkasan Transaksi Masuk & Keluar</div>
        <div style="color: #718096;">
            Periode:
            {{ $filterType === 'month' ? \Carbon\Carbon::create(null, $selectedMonth)->translatedFormat('F') : '' }}
            {{ $selectedYear }}
        </div>
    </div>

    {{-- BAGIAN BARANG MASUK --}}
    <div class="section-title">I. Ringkasan Barang Masuk (Per Supplier)</div>
    @foreach ($masukPerSupplier as $jenis => $suppliers)
        <div style="margin-left: 10px; font-weight: bold; color: #2b6cb0; margin-top: 10px;">• Kategori Supplier:
            {{ $jenis }}</div>
        @foreach ($suppliers as $s)
            <div class="entity-header">Supplier: {{ $s['nama_supplier'] }}</div>
            <table>
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="40%">Nama Barang</th>
                        <th class="text-right">Qty Diterima</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($s['details'] as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d M Y') }}</td>
                            <td>{{ $item->Inventory->Barang->nama_barang }}</td>
                            <td class="text-right">{{ number_format($item->jumlah_diterima, 2) }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="summary-row">
                        <td colspan="2">TOTAL UNTUK {{ $s['nama_supplier'] }}</td>
                        <td class="text-right">{{ number_format($s['total_qty'], 2) }}</td>
                        <td></td>
                        <td class="text-right">Rp {{ number_format($s['total_nilai'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach

    <div style="page-break-before: always;"></div>

    {{-- BAGIAN BARANG KELUAR --}}
    <div class="section-title">II. Ringkasan Barang Keluar (Per Customer)</div>
    @foreach ($keluarPerCostumer as $c)
        <div class="entity-header">Customer: {{ $c['nama_costumer'] }}</div>
        <table>
            <thead>
                <tr>
                    <th width="15%">Tanggal</th>
                    <th width="40%">Nama Barang</th>
                    <th class="text-right">Qty Keluar</th>
                    <th class="text-right">Konversi (Kg)</th>
                    <th class="text-right">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($c['details'] as $item)
                    @php
                        $barang = optional(optional($item->DetailInventory)->Inventory)->Barang;
                        $konversi = in_array(optional($barang->JenisBarang)->kode, ['FG', 'WIP', 'EC'])
                            ? $item->jumlah_keluar * ($barang->nilai_konversi ?? 1)
                            : 0;
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('d M Y') }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td class="text-right">{{ number_format($item->jumlah_keluar, 2) }}</td>
                        <td class="text-right">{{ $konversi > 0 ? number_format($konversi, 2) : '-' }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="summary-row">
                    <td colspan="2">TOTAL UNTUK {{ $c['nama_costumer'] }}</td>
                    <td class="text-right">{{ number_format($c['total_qty'], 2) }}</td>
                    <td class="text-right">{{ number_format($c['total_kg'], 2) }} Kg</td>
                    <td class="text-right">Rp {{ number_format($c['total_nilai'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        Waktu Cetak: {{ now()->translatedFormat('d F Y H:i') }} | Halaman PDF Transaksi
    </div>
</body>

</html>
