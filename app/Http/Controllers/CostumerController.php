<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Imports\CostumerImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CostumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Filter Dropdown Perusahaan (Untuk Super Admin tampil semua, untuk yang lain hanya miliknya)
        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        } else {
            $perusahaan = Perusahaan::where('id', $user->id_perusahaan)->whereNull('deleted_at')->get();
        }

        $query = Costumer::withTrashed();

        // 2. PROTEKSI ROLE: Jika bukan Super Admin, WAJIB filter berdasarkan id_perusahaan user
        if (!$user->hasRole('Super Admin')) {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }
        // Jika Super Admin dan sedang memilih filter perusahaan tertentu
        elseif ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // 3. Filter Status (Aktif / Tidak Aktif)
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'tidak_aktif') {
                $query->onlyTrashed();
            }
        } else {
            $query->whereNull('deleted_at');
        }

        // 4. Fitur Search
        $query->when($request->search, function ($q) use ($request) {
            $search = strtolower($request->search);
            $q->where(function ($inner) use ($search) {
                $inner->whereRaw('LOWER(nama_costumer) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        });

        $costumer = $query->latest()->paginate(10)->withQueryString();

        return view('pages.costumer.index', compact('costumer', 'perusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'nama_costumer' => 'required|string',
            'kode'          => 'required',
        ]);

        // Mengubah semua nilai menjadi uppercase dan menangani string kosong menjadi null
        $validated = array_map(function ($value) {
            if (is_string($value)) {
                return strtoupper(trim($value));
            }
            return $value === "" ? null : $value;
        }, $validated);

        Costumer::create($validated);

        return redirect()->back()->with('success', 'Costumer berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $costumer = Costumer::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'nama_costumer' => 'required|string',
            'kode' => 'required',
        ]);

        // Mengubah semua nilai menjadi uppercase dan menangani string kosong menjadi null
        $validated = array_map(function ($value) {
            if (is_string($value)) {
                return strtoupper(trim($value));
            }
            return $value === "" ? null : $value;
        }, $validated);

        $costumer->update($validated);

        return redirect()->back()->with('success', 'Costumer berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $costumer = Costumer::withTrashed()->findOrFail($id);
        $costumer->delete();

        return redirect()->back()->with('success', 'Costumer berhasil dihapus');
    }

    public function activate($id)
    {
        $costumer = Costumer::withTrashed()->findOrFail($id);

        $costumer->deleted_at = null;
        $costumer->save();

        return redirect()->back()->with('success', 'Costumer berhasil diaktifkan kembali.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class implements WithHeadings, WithEvents, WithStyles, WithTitle {

            public function title(): string
            {
                return 'Template Import Costumer';
            }

            public function headings(): array
            {
                return [
                    'nama_costumer',
                    'kode'
                ];
            }

            public function styles(Worksheet $sheet)
            {
                return [
                    // Style untuk Header (Baris 1)
                    1 => [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '4F46E5'], // Warna Indigo yang sama
                        ],
                        'alignment' => ['horizontal' => 'center']
                    ],
                ];
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
                        $rowCount = 100;

                        // 1. Setup Lebar Kolom Otomatis agar rapi
                        foreach (range('A', 'B') as $col) {
                            $sheet->getColumnDimension($col)->setAutoSize(true);
                        }

                        // 2. Setup Pesan Input (Tooltip) agar user paham aturan validasi
                        $validationNama = $sheet->getCell('A2')->getDataValidation();
                        $validationNama->setShowInputMessage(true)
                            ->setPromptTitle('Aturan Nama')
                            ->setPrompt('Wajib diisi dan tidak boleh sama dengan costumer yang sudah ada.');

                        $validationKode = $sheet->getCell('B2')->getDataValidation();
                        $validationKode->setShowInputMessage(true)
                            ->setPromptTitle('Aturan Kode')
                            ->setPrompt('Wajib diisi, unik, dan disarankan menggunakan format konsisten.');

                        // 3. Terapkan Border dan Tooltip ke 100 baris
                        for ($i = 2; $i <= $rowCount; $i++) {
                            $sheet->getCell("A$i")->setDataValidation(clone $validationNama);
                            $sheet->getCell("B$i")->setDataValidation(clone $validationKode);

                            // Beri border tipis pada area input
                            $sheet->getStyle("A$i:B$i")->getBorders()->getAllBorders()
                                ->setBorderStyle(Border::BORDER_THIN);
                        }
                    },
                ];
            }
        }, 'template_costumer_v2.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $import = new CostumerImport;
            Excel::import($import, $request->file('file'));

            $berhasil = $import->getRowCount();
            $gagal = $import->failures()->count();

            if ($gagal > 0) {
                $details = collect($import->failures())->map(function ($failure) {
                    return "<li class='mb-1'><b>Baris " . $failure->row() . ":</b> " . implode(", ", $failure->errors()) . "</li>";
                })->implode('');

                return back()->with('error_import', [
                    'title' => "Hasil Import: $berhasil Berhasil, $gagal Gagal",
                    'success_count' => $berhasil,
                    'fail_count' => $gagal,
                    'html' => "<ul class='text-left text-xs list-none p-0'>" . $details . "</ul>"
                ]);
            }

            return back()->with('success', "Berhasil mengimpor $berhasil data costumer.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
