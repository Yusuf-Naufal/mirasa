<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $firstItem->Perusahaan->nama_perusahaan ?? $perusahaan->nama_perusahaan }}</title>
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
            padding: 5px;
            border-right: 1px solid #000;
        }

        .company-info h1 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
        }

        .company-info p {
            margin: 1px 0;
            font-size: 9px;
        }

        .doc-meta {
            font-size: 9px;
            width: 200px;
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

        .info-left,
        .info-right-box {
            flex: 1;
            padding: 5px;
        }

        .info-right-box {
            border: 1px solid #000;
            max-width: 300px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1px 2px;
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

        .footer-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .footer-grid td {
            width: 33.33%;
            text-align: center;
            height: 60px;
            vertical-align: top;
        }

        .signature-line {
            margin-top: 35px;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    @php
        // Prioritaskan nama perusahaan dari relasi BarangKeluar
        $currentPerusahaan = $firstItem->Perusahaan ?? $perusahaan;
    @endphp

    <div class="wrapper">
        <table class="header-table">
            <tr>
                <td class="logo-section">
                    <img src="{{ $currentPerusahaan->logo ? asset('storage/' . $currentPerusahaan->logo) : asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}"
                        style="width: 50px;">
                </td>
                <td class="company-info" style="padding-left: 10px;">
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
                            <td>: 1</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="title-container">
            <div class="title">SURAT JALAN</div>
            <div class="title-date">{{ $currentPerusahaan->kota }},
                {{ \Carbon\Carbon::parse($firstItem->tanggal_keluar)->format('d-m-Y') }}</div>
        </div>

        <div class="info-grid">
            <div class="info-left">
                <table class="info-table">
                    <tr>
                        <td width="80">NO. FAKTUR</td>
                        <td>: {{ $firstItem->no_faktur ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>NO. PO</td>
                        <td>: {{ $firstItem->no_jalan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="info-right-box">
                <table class="info-table">
                    <tr>
                        <td>Kepada Yth.</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">
                            {{ $firstItem->Costumer->nama_costumer ?? ($firstItem->Perusahaan->nama_perusahaan ?? 'Pelanggan Umum') }}
                        </td>
                    </tr>
                    <tr style="height: 5px;"></tr>
                    <tr>
                        <td style="text-align: center; border: 1px solid #000; font-weight: bold;">SOPIR:
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
                    <th colspan="2">BANYAKNYA</th>
                    <th colspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th width="70">KARTON</th>
                    <th width="70">KG</th>
                    <th width="90">TGL PROD.</th>
                    <th width="80">VARIETAS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalKtn = 0;
                    $totalKg = 0;
                @endphp
                @foreach ($items as $index => $item)
                    @php
                        $satuan = strtoupper($item->DetailInventory->Inventory->Barang->satuan);
                        // Logika pemetaan satuan: Karton masuk KTN, selain itu masuk KG
                        $qtyKtn = ($satuan == 'KARTON' || $satuan == 'KTN') ? $item->jumlah_keluar : 0;
                        $qtyKg = ($qtyKtn == 0) ? $item->jumlah_keluar : 0;
                        
                        $totalKtn += (float) $qtyKtn;
                        $totalKg += (float) $qtyKg;
                    @endphp
                    <tr style="height: 35px;">
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left; padding-left: 5px;">
                            {{ $item->DetailInventory->Inventory->Barang->nama_barang }}</td>
                        <td>{{ $qtyKtn > 0 ? number_format($qtyKtn, 0, ',', '.') : '-' }}</td>
                        <td>{{ $qtyKg > 0 ? number_format($qtyKg, 0, ',', '.') : '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->DetailInventory->tanggal_masuk)->format('d-M-y') }}</td>
                        <td>{{ $keterangan->varietas }}</td>
                    </tr>
                @endforeach
                
                {{-- Baris Kosong Tambahan untuk estetika cetakan --}}
                <tr style="height: 35px;">
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background: #eee;">
                    <td colspan="2">TOTAL</td>
                    <td>{{ $totalKtn > 0 ? number_format($totalKtn, 0, ',', '.') : '-' }}</td>
                    <td>{{ $totalKg > 0 ? number_format($totalKg, 0, ',', '.') : '-' }}</td>
                    <td colspan="2"></td>
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

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #333; color: #fff; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
            CETAK SURAT JALAN
        </button>
    </div>
</body>

</html>