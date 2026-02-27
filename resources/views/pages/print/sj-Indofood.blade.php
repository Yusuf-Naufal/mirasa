<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Jalan & Faktur - {{ $firstItem->Perusahaan->nama_perusahaan ?? $perusahaan->nama_perusahaan }}
    </title>
    <style>
        /* Ukuran Kertas K4 PRS (Kuarto) */
        @page {
            size: 210mm 140mm;
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            margin: 0;
            padding: 5mm;
            background-color: #fff;
            color: #000;
        }

        .wrapper {
            width: 100%;
            border: 1px solid #000;
            padding: 2px;
            box-sizing: border-box;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            border-collapse: collapse;
        }

        .logo-section {
            width: 80px;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
            border-right: 1px solid #000;
        }

        .company-info {
            padding-left: 10px;
            vertical-align: middle;
        }

        .company-info h1 {
            font-size: 14px;
            margin: 0;
            letter-spacing: 1px;
        }

        .company-info p {
            margin: 1px 0;
            font-size: 9px;
        }

        .doc-meta {
            font-size: 9px;
            width: 200px;
            padding-top: 5px;
            padding-left: 10px;
            border-left: 1px solid #000;
            vertical-align: top;
        }

        .doc-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .title-container {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 5px 0;
            height: 30px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 4px;
        }

        .title-date {
            position: absolute;
            right: 10px;
            font-weight: bold;
            font-size: 10px;
        }

        .info-grid {
            width: 100%;
            display: flex;
        }

        .info-left {
            flex: 1;
            padding: 5px;
        }

        .info-right-box {
            width: 300px;
            padding: 5px;
            border: 1px solid #000;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1px 2px;
            vertical-align: top;
        }

        .kendaraan-bar {
            padding: 5px;
            border-bottom: 1px solid #000;
            font-size: 10px;
        }

        .grid-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grid-table th,
        .grid-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
        }

        .grid-table thead th {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-grid td {
            width: 33.33%;
            text-align: center;
            height: 70px;
            vertical-align: top;
            padding-top: 5px;
        }

        .signature-line {
            margin-top: 40px;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .wrapper {
                border: 1px solid #000;
            }
        }
    </style>
</head>

<body>
    @php
        $currentPerusahaan = $firstItem->Perusahaan ?? $perusahaan;
    @endphp

    {{-- SURAT JALAN (Bagian Atas) --}}
    <div class="wrapper" style="margin-bottom: 20px;">
        <table class="header-table">
            <tr>
                <td class="logo-section">
                    <img src="{{ $currentPerusahaan->logo ? asset('storage/' . $currentPerusahaan->logo) : asset('assets/logo/Mirasa-logo.webp') }}"
                        style="width: 50px;">
                </td>
                <td class="company-info">
                    <h1>{{ $currentPerusahaan->nama_perusahaan }}</h1>
                    <p>{{ $currentPerusahaan->alamat }}</p>
                    <p>Telp. 0293-3280554, 3280556 | Fax. 0293-782614</p>
                </td>
                <td class="doc-meta">
                    <table>
                        <tr>
                            <td>No. Doc</td>
                            <td>: MFI/QMS/F.8.2.0.2</td>
                        </tr>
                        <tr>
                            <td>No. Rev</td>
                            <td>: 0</td>
                        </tr>
                        <tr>
                            <td>Tgl. Terbit</td>
                            <td>: 01-07-2022</td>
                        </tr>
                        <tr>
                            <td>Hal</td>
                            <td>: 1 </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="title-container">
            <div class="title">SURAT JALAN</div>
            <div class="title-date">
                {{ $currentPerusahaan->kota }}, {{ \Carbon\Carbon::parse($firstItem->tanggal_keluar)->format('d-m-Y') }}
            </div>
        </div>

        <div class="info-grid">
            <div class="info-left">
                <table class="info-table">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>

            <div class="info-right-box">
                <table class="info-table">
                    <tr>
                        <td>Kepada Yth.</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; font-size: 11px;">
                            {{ $firstItem->Costumer->nama_costumer ?? ($firstItem->Perusahaan->nama_perusahaan ?? 'Pelanggan Umum') }}
                        </td>
                    </tr>
                    <tr style="height: 5px;"></tr>
                    <tr>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-weight: bold;">SOPIR :
                            {{ $keterangan->nama_supir }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="kendaraan-bar">
            Dengan Kendaraan {{ $keterangan->jenis_kendaraan }} No. Polisi: <b>{{ $keterangan->plat_kendaraan }}</b>
            kami kirim barang sbb:
        </div>

        <table class="grid-table">
            <thead>
                <tr>
                    <th rowspan="2" width="30">No.</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th colspan="4">BANYAKNYA</th>
                    <th rowspan="2" width="150">KETERANGAN</th>
                </tr>
                <tr>
                    <th width="60">IKAT</th>
                    <th width="60">BUNGKUS</th>
                    <th width="60">KARTON</th>
                    <th width="60">BALL/KG</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tIkat = 0;
                    $tBks = 0;
                    $tKtn = 0;
                    $tBall = 0;
                @endphp
                @foreach ($items as $index => $item)
                    @php
                        $u = strtoupper($item->DetailInventory->Inventory->Barang->satuan);
                        $ikat = $u == 'IKAT' ? $item->jumlah_keluar : 0;
                        $bks = in_array($u, ['BUNGKUS', 'BKS']) ? $item->jumlah_keluar : 0;
                        $ktn = in_array($u, ['KARTON', 'KTN']) ? $item->jumlah_keluar : 0;
                        $ball = $ikat == 0 && $bks == 0 && $ktn == 0 ? $item->jumlah_keluar : 0;
                        $tIkat += $ikat;
                        $tBks += $bks;
                        $tKtn += $ktn;
                        $tBall += $ball;
                    @endphp
                    <tr style="height: 35px;">
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left; padding-left: 5px;">
                            {{ $item->DetailInventory->Inventory->Barang->nama_barang }}</td>
                        <td>{{ $ikat ?: '' }}</td>
                        <td>{{ $bks ?: '' }}</td>
                        <td>{{ $ktn ?: '' }}</td>
                        <td>{{ $ball ?: '' }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr style="height: 35px;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td colspan="2">TOTAL</td>
                    <td>{{ $tIkat ?: '' }}</td>
                    <td>{{ $tBks ?: '' }}</td>
                    <td>{{ $tKtn ?: '' }}</td>
                    <td>{{ $tBall ?: '' }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <table class="footer-grid">
            <tr>
                <td>Penerima,<div class="signature-line">( ..................... )</div>
                </td>
                <td>Sopir/Sales,<div class="signature-line">( ..................... )</div>
                </td>
                <td>Bagian Gudang,<div class="signature-line">( ..................... )</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- INVOICE / FAKTUR (Bagian Bawah) --}}
    <div class="wrapper">
        <table class="header-table">
            <tr>
                <td class="logo-section">
                    <img src="{{ $currentPerusahaan->logo ? asset('storage/' . $currentPerusahaan->logo) : asset('assets/logo/Mirasa-logo.webp') }}"
                        style="width: 50px;">
                </td>
                <td class="company-info">
                    <h1>{{ $currentPerusahaan->nama_perusahaan }}</h1>
                    <p>{{ $currentPerusahaan->alamat }}</p>
                    <p>Telp. 0293-3280554, 3280556 | Fax. 0293-782614</p>
                </td>
                <td class="doc-meta">
                    <table>
                        <tr>
                            <td>No. Doc</td>
                            <td>: MFI/QMS/F.8.2.0.2</td>
                        </tr>
                        <tr>
                            <td>No. Rev</td>
                            <td>: 0</td>
                        </tr>
                        <tr>
                            <td>Tgl. Terbit</td>
                            <td>: 01-07-2022</td>
                        </tr>
                        <tr>
                            <td>Hal</td>
                            <td>: 1 </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="title-container">
            <div class="title"></div>
            <div class="title-date">
                {{ $currentPerusahaan->kota }},
                {{ \Carbon\Carbon::parse($firstItem->tanggal_keluar)->format('d-m-Y') }}
            </div>
        </div>

        <div class="info-grid">
            <div class="info-left">
                <table class="info-table">
                    <tr>
                        <td width="100">NOMOR FAKTUR</td>
                        <td style="border: 1px solid #000; padding-left: 5px;">: {{ $firstItem->no_faktur ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td>NOMOR PO</td>
                        <td style="border: 1px solid #000; padding-left: 5px;">: {{ $firstItem->no_jalan ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="info-right-box">
                <table class="info-table">
                    <tr>
                        <td>Kepada Yth.</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; font-size: 11px;">
                            {{ $firstItem->Costumer->nama_costumer ?? 'Pelanggan Umum' }}</td>
                    </tr>
                    <tr style="height: 5px;"></tr>
                    <tr>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-weight: bold;">SOPIR :
                            {{ $keterangan->nama_supir }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="grid-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th rowspan="2" width="30">No.</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th colspan="4">BANYAKNYA</th>
                    <th rowspan="2" width="80">HARGA</th>
                    <th rowspan="2" width="100">JUMLAH (Rp)</th>
                </tr>
                <tr>
                    <th width="40">IKAT</th>
                    <th width="40">BUNGKUS</th>
                    <th width="50">KARTON</th>
                    <th width="40">BALL</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDPP = 0; @endphp
                @foreach ($items as $index => $item)
                    @php
                        $u = strtoupper($item->DetailInventory->Inventory->Barang->satuan);
                        $ikat = $u == 'IKAT' ? $item->jumlah_keluar : 0;
                        $bks = in_array($u, ['BUNGKUS', 'BKS']) ? $item->jumlah_keluar : 0;
                        $ktn = in_array($u, ['KARTON', 'KTN']) ? $item->jumlah_keluar : 0;
                        $ball = $ikat == 0 && $bks == 0 && $ktn == 0 ? $item->jumlah_keluar : 0;
                        $totalDPP += $item->total_harga;
                    @endphp
                    <tr style="height: 30px;">
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left; padding-left: 5px;">
                            {{ $item->DetailInventory->Inventory->Barang->nama_barang }}</td>
                        <td>{{ $ikat ?: '' }}</td>
                        <td>{{ $bks ?: '' }}</td>
                        <td>{{ $ktn ?: '' }}</td>
                        <td>{{ $ball ?: '' }}</td>
                        <td style="text-align: right; padding-right: 5px;">
                            {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td style="text-align: right; padding-right: 5px;">
                            {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="height: 30px;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                @php $ppnVal = ($totalDPP * (float)$ppnPercent) / 100; @endphp
                <tr>
                    <td colspan="2" rowspan="3" style="text-align: left; vertical-align: top; padding: 5px;">
                        <div style="border: 1px solid #000; padding: 5px; height: 100%;">
                            <strong>Sistem pembayaran:</strong><br>[ ] Tunai [ ] Cek / BG
                        </div>
                    </td>
                    <td colspan="4" style="font-weight: bold;"></td>
                    <td style="font-weight: bold; text-align: left; padding-left: 5px;">DPP</td>
                    <td style="text-align: right; padding-right: 5px;">{{ number_format($totalDPP, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none;"></td>
                    <td style="font-weight: bold; text-align: left; padding-left: 5px;">PPN {{ $ppnPercent }}%</td>
                    <td style="text-align: right; padding-right: 5px;">{{ number_format($ppnVal, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="4" style="border: none;"></td>
                    <td style="text-align: left; padding-left: 5px;">JUMLAH DIBAYAR</td>
                    <td style="text-align: right; padding-right: 5px;">
                        {{ number_format($totalDPP + $ppnVal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 10px;">
            <div style="width: 60%; border: 1px solid #000; padding: 5px; font-size: 8.5px;">
                <strong>NB:</strong> Untuk pembayaran via Transfer/BG mohon ke rekening an.
                <strong>{{ $currentPerusahaan->nama_perusahaan }}</strong><br>
                BCA AC. 1049173332
            </div>
            <div style="width: 30%; text-align: center;">
                <p style="font-size: 9px; margin-bottom: 40px;">Bag. Keuangan</p>
                <p>( ..................... )</p>
            </div>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()"
            style="padding: 10px 20px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 5px; font-weight: bold;">
            CETAK DOKUMEN
        </button>
    </div>
</body>

</html>
