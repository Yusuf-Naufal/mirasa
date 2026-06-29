<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Absensi_Dan_Gaji_Karyawan</title>
    <style>
        /* GAYA DESAIN UTAMA (PENGGANTI TAILWIND AGAR TIDAK POLOS) */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            padding: 30px;
            margin: 0;
            color: #1f2937;
        }
        .no-print-container {
            max-width: 900px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: flex-end;
        }
        .btn-print {
            background-color: #059669;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-print:hover { background-color: #047857; }
        
        /* KERTAS A4 */
        .kertas-a4 {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        /* KOP SURAT DOKUMEN */
        .header-kop {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #111827;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-kop h1 { margin: 0; font-size: 26px; font-weight: 900; tracking-tight: -0.05em; }
        .header-kop p { margin: 5px 0 0 0; font-size: 14px; font-weight: bold; color: #6b7280; }
        .status-title { font-size: 11px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; }
        .status-val { margin: 2px 0 0 0; font-size: 15px; font-weight: 900; color: #059669; text-transform: uppercase; }

        /* PERIODE BOX */
        .box-periode {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 30px;
        }
        .box-title { font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .box-val { margin: 0; font-weight: bold; color: #374151; }

        /* GRID TIGA KOTAK GAJI (PERSIS SEPERTI GAMBAR CONTOH) */
        .grid-gaji {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 35px;
        }
        .card-gaji { padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; }
        .gaji-total { background-color: #e0e7ff; border-color: #c7d2fe; }
        .gaji-langsung { background-color: #ffedd5; border-color: #fed7aa; }
        .gaji-tidak { background-color: #f3f4f6; border-color: #e5e7eb; }
        .lbl-gaji { font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .lbl-gaji.t { color: #4f46e5; } .lbl-gaji.l { color: #ea580c; } .lbl-gaji.tl { color: #4b5563; }
        .val-gaji { margin: 0; font-size: 18px; font-weight: 900; }
        .val-gaji.t { color: #3730a3; } .val-gaji.l { color: #9a3412; } .val-gaji.tl { color: #1f2937; }

        /* TABEL RESMI */
        .sub-judul { font-size: 13px; font-weight: 900; text-transform: uppercase; border-left: 4px solid #111827; padding-left: 8px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 12px; margin-bottom: 40px; }
        th { background-color: #111827; color: white; padding: 10px; font-weight: bold; text-transform: uppercase; font-size: 11px; border: 1px solid #111827; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* TANDA TANGAN */
        .wrapper-ttd { display: grid; grid-template-columns: 1fr 1fr; font-size: 12px; text-align: center; margin-top: 50px; }
        .space-ttd { margin-top: 70px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }

        /* LOGIKA AUTOMATIS SAAT TOMBOL PRINT DIKLIK */
        @media print {
            body { background-color: white; padding: 0; margin: 0; }
            .no-print { display: none !important; }
            .kertas-a4 { border: none !important; box-shadow: none !important; padding: 0 !important; max-width: 100% !important; }
            th { background-color: #111827 !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .card-gaji { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .box-periode { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <!-- TOMBOL ATAS (HILANG SAAT PRINTING) -->
    <div class="no-print-container no-print">
        <button onclick="window.print()" class="btn-print">
            Klik Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <!-- STRUKTUR KERTAS LAPORAN -->
    <div class="kertas-a4">
        
        <!-- HEADER KOP -->
        <div class="header-kop">
            <div>
                <h1>PT MIRASA FOOD INDUSTRY</h1>
                <p>Laporan Absensi & Penggajian Karyawan</p>
            </div>
            <div>
                <span class="status-title">Status Dokumen</span>
                <p class="status-val">Finalized</p>
            </div>
        </div>

        <!-- INFO PERIODE -->
        <div class="box-periode">
            <div>
                <span class="box-title">Periode Laporan</span>
                <p class="box-val">
                    {{ $request->filter_tanggal ? \Carbon\Carbon::parse($request->filter_tanggal)->translatedFormat('d F Y') : \Carbon\Carbon::today()->translatedFormat('d F Y') }}
                </p>
            </div>
            <div style="text-align: right;">
                <span class="box-title">Dibuat Pada</span>
                <p class="box-val" style="font-weight: normal; color: #4b5563;">{{ date('d/m/Y H:i') }} WIB</p>
            </div>
        </div>

        <!-- TIGA KOTAK INFO GAJI BESAR (PERSIS SEPERTI MAP PRODUKSI) -->
        <div class="grid-gaji">
            <div class="card-gaji gaji-total">
                <span class="lbl-gaji t">Total Gaji Keseluruhan</span>
                <p class="val-gaji t">Rp {{ number_format($attendances->sum('nominal_gaji'), 0, ',', '.') }}</p>
            </div>
            <div class="card-gaji gaji-langsung">
                <span class="lbl-gaji l">Total Gaji Langsung</span>
                <p class="val-gaji l">Rp {{ number_format($attendances->where('kelompok', 'langsung')->sum('nominal_gaji'), 0, ',', '.') }}</p>
            </div>
            <div class="card-gaji gaji-tidak">
                <span class="lbl-gaji tl">Total Gaji Tidak Langsung</span>
                <p class="val-gaji tl">Rp {{ number_format($attendances->where('kelompok', 'tidak langsung')->sum('nominal_gaji'), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- TABEL RINCIAN DATA -->
        <div class="sub-judul">I. RINCIAN REKAPITULASI KEHADIRAN & GAJI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 8%;">ID Karyawan</th>
                    <th style="width: 15%;">Nama Karyawan</th>
                    <th style="width: 10%;">Devisi</th>
                    <th style="width: 14%;">Kelompok</th>
                    <th style="width: 10%;">Shift</th>
                    <th style="width: 13%;">Jam Kerja</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 18%; text-align: right;">Nominal Gaji</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                    <td style="font-weight: bold; color: #4b5563;">{{ $item->id_karyawan }}</td>
                    <td style="font-weight: bold; color: #111827;">{{ $item->nama_karyawan }}</td>
                    <td>{{ $item->devisi }}</td>
                    <td style="text-transform: uppercase; font-weight: 500; color: #4b5563;">{{ $item->kelompok }}</td>
                    <td style="font-weight: bold; color: #7c3aed;">SHIFT {{ $item->shift }}</td>
                    <td>{{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-' }} - {{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '-' }}</td>
                    <td style="font-weight: bold; text-transform: uppercase; color: {{ $item->keterangan == 'hadir' ? '#059669' : '#d97706' }};">{{ $item->keterangan }}</td>
                    <td style="font-weight: bold; color: #111827;">Rp {{ number_format($item->nominal_gaji, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center" style="padding: 20px; color: #9ca3af;">Tidak ada rincian data absensi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN BAWAH -->
        <div class="wrapper-ttd">
            <div></div>
            <div>
                <p style="margin: 0;">Mengetahui,<br><span style="font-weight: bold; color: #374151;">Kabag. Keuangan PT Mirasa Food Industry</span></p>
                <p class="space-ttd">( ............................................ )</p>
            </div>
            <!-- TAMBAHKAN KODE CATATAN KAKI DI SINI -->
            <div style="margin-top: 50px; text-align: center; font-size: 11px; color: #555; font-style: italic; border-top: 1px dashed #ccc; padding-top: 10px;">
                *Dokumen ini tidak boleh dicopy atau disebarluaskan tanpa izin dari perusahaan.
            </div>
        </div>

    </div>
</body>
</html>
