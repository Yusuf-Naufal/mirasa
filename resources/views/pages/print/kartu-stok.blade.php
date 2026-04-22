<!DOCTYPE html>
<html>

<head>
    <title>Kartu Stok PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .bg-gray {
            background-color: #f2f2f2;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>KARTU PERSEDIAAN BARANG</h2>
        <h3>{{ $inventory->Perusahaan->nama_perusahaan }}</h3>
    </div>

    <table style="border: none; margin-bottom: 10px;">
        <tr style="border: none;">
            <td style="border: none; text-align: left; width: 50%;">
                <strong>Nama Barang:</strong> {{ $inventory->Barang->nama_barang }}<br>
                <strong>Satuan:</strong> {{ $inventory->Barang->satuan ?? 'PCS' }}
            </td>
            <td style="border: none; text-align: right;">
                <strong>Periode:</strong> {{ $namaBulan }} {{ $tahun }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr class="bg-gray">
                <th rowspan="2" class="text-center">Tanggal</th>
                <th rowspan="2" class="text-center">Keterangan / Batch</th>
                <th colspan="3" class="text-center">Masuk (IN)</th>
                <th colspan="3" class="text-center">Keluar (OUT)</th>
                <th colspan="3" class="text-center">Saldo</th>
            </tr>
            <tr class="bg-gray">
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $runningNilai = $saldoAwalNilai; @endphp
            <tr>
                <td class="text-center">01/{{ $bulan }}</td>
                <td class="text-left italic">SALDO AWAL</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td class="font-bold">{{ number_format($saldoAwalValue, 2) }}</td>
                <td>-</td>
                <td class="font-bold">{{ number_format($runningNilai, 0) }}</td>
            </tr>
            @foreach ($mutasi as $log)
                @php
                    $qtyAbs = abs($log->qty);
                    $harga = $log->source?->harga ?? 0;
                    $total = $qtyAbs * $harga;
                    $runningNilai += $log->qty > 0 ? $total : -$total;
                @endphp
                <tr>
                    <td class="text-center">{{ date('d/m/Y', strtotime($log->tanggal_transaksi)) }}</td>
                    <td class="text-left">{{ $log->nomor_batch ?? $log->keterangan }}</td>
                    {{-- Masuk --}}
                    <td>{{ $log->qty > 0 ? number_format($qtyAbs, 2) : '-' }}</td>
                    <td>{{ $log->qty > 0 ? number_format($harga, 0) : '-' }}</td>
                    <td>{{ $log->qty > 0 ? number_format($total, 0) : '-' }}</td>
                    {{-- Keluar --}}
                    <td>{{ $log->qty < 0 ? number_format($qtyAbs, 2) : '-' }}</td>
                    <td>{{ $log->qty < 0 ? number_format($harga, 0) : '-' }}</td>
                    <td>{{ $log->qty < 0 ? number_format($total, 0) : '-' }}</td>
                    
                    {{-- Saldo --}}
                    <td class="font-bold">{{ number_format($log->saldo_qty, 2) }}</td>
                    <td>{{ number_format($log->saldo_qty > 0 ? $runningNilai / $log->saldo_qty : 0, 0) }}</td>
                    <td class="font-bold">{{ number_format($runningNilai, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
