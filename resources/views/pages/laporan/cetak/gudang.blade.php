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
            line-height: 1.4;
        }

        .header {
            border-bottom: 2px solid #2d3748;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        /* Summary Box */
        .summary-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-box {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
        }

        .summary-title {
            font-size: 8pt;
            color: #718096;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 12pt;
            font-weight: bold;
            color: #2d3748;
        }

        h3 {
            border-left: 4px solid #2d3748;
            padding-left: 10px;
            text-transform: uppercase;
            font-size: 10pt;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #2d3748;
            color: white;
            padding: 8px;
            text-align: left;
            text-transform: uppercase;
            font-size: 8pt;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            background: #e2e8f0;
            font-size: 7pt;
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
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">{{ $namaPerusahaan }}</h1>
        <div style="font-size: 12pt;">Laporan Inventaris & Stok Gudang</div>
        <div style="color: #718096;">
            Periode:
            @php
                $dates = explode(' to ', $dateRange);
                $start = \Carbon\Carbon::parse($dates[0])->translatedFormat('d F Y');
                $end = isset($dates[1]) ? \Carbon\Carbon::parse($dates[1])->translatedFormat('d F Y') : $start;
                echo $start === $end ? $start : "$start - $end";
            @endphp
        </div>
    </div>

    <table class="summary-container">
        <tr>
            <td style="border:none; padding:0; width: 32%;">
                <div class="summary-box">
                    <div class="summary-title">Total Nilai Aset</div>
                    <div class="summary-value">Rp {{ number_format($summary['total_asset'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border:none; width: 2%;"></td>
            <td style="border:none; padding:0; width: 20%;">
                <div class="summary-box">
                    <div class="summary-title">Produksi</div>
                    <div class="summary-value">{{ $summary['count_produksi'] }} <span
                            style="font-size: 8pt; font-weight: normal;">SKU</span></div>
                </div>
            </td>
            <td style="border:none; width: 2%;"></td>
            <td style="border:none; padding:0; width: 20%;">
                <div class="summary-box">
                    <div class="summary-title">Bahan Baku</div>
                    <div class="summary-value">{{ $summary['count_bb'] }} <span
                            style="font-size: 8pt; font-weight: normal;">SKU</span></div>
                </div>
            </td>
            <td style="border:none; width: 2%;"></td>
            <td style="border:none; padding:0; width: 20%;">
                <div class="summary-box">
                    <div class="summary-title">Penolong</div>
                    <div class="summary-value">{{ $summary['count_bp'] }} <span
                            style="font-size: 8pt; font-weight: normal;">SKU</span></div>
                </div>
            </td>
        </tr>z
    </table>

    @foreach ($stokGlobalGrouped as $kategori => $items)
        <h3>Inventaris: {{ $kategori }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th class="text-right">Total Stok</th>
                    <th class="text-right">Subtotal Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td><strong>{{ $item->Barang->nama_barang }}</strong></td>
                        <td><span class="badge">{{ $item->Barang->satuan }}</span></td>
                        <td class="text-right">{{ number_format($item->stok, 2) }}</td>
                        <td class="text-right" style="font-weight: bold;">
                            Rp {{ number_format($item->total_nilai_asset ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    {{-- BAGIAN RINCIAN AKTIVITAS LOG --}}
    <div style="page-break-before: always;">
        <h3>III. Rincian Aktivitas Log (Masuk & Keluar)</h3>

        @php
            // Mengelompokkan barang masuk berdasarkan id_barang
            $logMasuk = $stokDetail->groupBy('Inventory.id_barang')->map(function ($group) {
                return [
                    'nama_barang' => $group->first()->Inventory->Barang->nama_barang,
                    'stok_masuk' => $group->sum('jumlah_diterima'),
                    'total_nilai' => $group->sum('total_harga'),
                    'satuan' => $group->first()->Inventory->Barang->satuan,
                ];
            });

            // Mengelompokkan barang keluar berdasarkan id_barang
            $logKeluar = $barangKeluar->groupBy('DetailInventory.Inventory.id_barang')->map(function ($group) {
                return [
                    'nama_barang' => $group->first()->DetailInventory->Inventory->Barang->nama_barang,
                    'stok_keluar' => $group->sum('jumlah_keluar'),
                    'total_nilai' => $group->sum('total_harga'),
                    'satuan' => $group->first()->DetailInventory->Inventory->Barang->satuan,
                ];
            });
        @endphp

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="background-color: #f7fafc; color: #2d3748; border: 1px solid #e2e8f0;">Nama Barang</th>
                    <th style="background-color: #f0fff4; color: #276749; border: 1px solid #e2e8f0;"
                        class="text-right">
                        Total Masuk</th>
                    <th style="background-color: #f0fff4; color: #276749; border: 1px solid #e2e8f0;"
                        class="text-right">
                        Nilai Masuk</th>
                    <th style="background-color: #fff5f5; color: #9b2c2c; border: 1px solid #e2e8f0;"
                        class="text-right">
                        Total Keluar</th>
                    <th style="background-color: #fff5f5; color: #9b2c2c; border: 1px solid #e2e8f0;"
                        class="text-right">
                        Nilai Keluar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Menggabungkan semua ID barang unik dari kedua log untuk baris tabel
                    $allBarangIds = $logMasuk->keys()->concat($logKeluar->keys())->unique();
                @endphp

                @foreach ($allBarangIds as $id)
                    @php
                        $masuk = $logMasuk->get($id);
                        $keluar = $logKeluar->get($id);
                        $nama = $masuk['nama_barang'] ?? ($keluar['nama_barang'] ?? 'Tidak Diketahui');
                        $satuan = $masuk['satuan'] ?? ($keluar['satuan'] ?? '-');
                    @endphp
                    <tr>
                        <td style="border: 1px solid #e2e8f0;">
                            <strong>{{ $nama }}</strong><br>
                            <span class="badge">{{ $satuan }}</span>
                        </td>
                        <td style="border: 1px solid #e2e8f0;" class="text-right">
                            {{ $masuk ? number_format($masuk['stok_masuk'], 2) : '0.00' }}
                        </td>
                        <td style="border: 1px solid #e2e8f0; color: #38a169;" class="text-right">
                            Rp {{ number_format($masuk['total_nilai'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td style="border: 1px solid #e2e8f0;" class="text-right">
                            {{ $keluar ? number_format($keluar['stok_keluar'], 2) : '0.00' }}
                        </td>
                        <td style="border: 1px solid #e2e8f0; color: #e53e3e;" class="text-right">
                            Rp {{ number_format($keluar['total_nilai'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach

                @if ($allBarangIds->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #a0aec0;">
                            Tidak ada aktivitas log pada periode ini.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem inventaris | {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>

</html>
