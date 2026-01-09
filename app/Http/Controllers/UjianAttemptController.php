<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianAttemptModel;
use App\Models\UjianModel;
use App\Models\KelasModel;
use App\Models\User;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;



class UjianAttemptController extends Controller
{
    public function index($ujianId)
    {
        $activeMenu = 'hasil_ujian';
        $title = 'Hasil Ujian';

        $ujian = UjianModel::with(['mataPelajaran', 'tahunAjaran', 'kelas'])
            ->findOrFail($ujianId);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Selesai', 'url' => route('admin.ujian.all_selesai')],
            ['label' => 'Hasil Ujian', 'url' => ''],
        ];

        /**
         * HITUNG PESERTA BERDASARKAN ujian_attempt
         * bukan siswa aktif sekarang
         */
        $kelasList = $ujian->kelas()
            ->get()
            ->map(function ($kelas) use ($ujianId) {
                $kelas->peserta_count = UjianAttemptModel::where('ujian_id', $ujianId)
                    ->where('kelas_id', $kelas->id)
                    ->count();

                return $kelas;
            });

        return view('admin.ujian.hasil-ujian', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'ujian',
            'kelasList'
        ));
    }


    public function detail(Request $request, $ujianId, $kelasId)
    {
        $activeMenu = 'hasil_ujian';
        $title = 'Detail Hasil Ujian';

        $ujian = UjianModel::with(['mataPelajaran', 'tahunAjaran'])
            ->findOrFail($ujianId);

        $kelas = KelasModel::findOrFail($kelasId);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Selesai', 'url' => route('admin.ujian.all_selesai')],
            ['label' => 'Hasil Ujian', 'url' => route('admin.ujian.hasil', $ujian->id)],
            ['label' => $kelas->nama_kelas, 'url' => ''],
        ];

        $perPage = $request->get('per_page', 5);
        $search  = $request->get('search');

        /**
         * Ambil siswa BERDASARKAN ujian_attempt
         */
        $siswa = User::query()
            ->select('users.*', 'ua.final_score')
            ->join('ujian_attempt as ua', function ($join) use ($ujianId, $kelasId) {
                $join->on('ua.user_id', '=', 'users.id')
                    ->where('ua.ujian_id', $ujianId)
                    ->where('ua.kelas_id', $kelasId); // snapshot kelas saat ujian
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.username', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('ua.final_score')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.ujian.siswa-hasil-ujian', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'ujian',
            'kelas',
            'siswa'
        ));
    }

    public function exportExcel($ujianId)
    {
        $ujian = UjianModel::with(['kelas', 'mataPelajaran', 'tahunAjaran'])->findOrFail($ujianId);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($ujian->kelas as $index => $kelas) {

            $sheet = $spreadsheet->createSheet($index);
            $sheet->setTitle(substr($kelas->nama_kelas, 0, 31));

            /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */
            $logoPath = public_path('logo-smancir.png');
            if (file_exists($logoPath)) {
                $drawing = new Drawing();
                $drawing->setName('Logo')
                    ->setDescription('Logo Sekolah')
                    ->setPath($logoPath)
                    ->setHeight(90)
                    ->setCoordinates('B2')
                    ->setWorksheet($sheet);

                for ($i = 2; $i <= 6; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | KOP SURAT
        |--------------------------------------------------------------------------
        */
            $sheet->mergeCells('C1:H1')->setCellValue('C1', 'PEMERINTAH PROVINSI BANTEN');
            $sheet->mergeCells('C2:H2')->setCellValue('C2', 'DINAS PENDIDIKAN DAN KEBUDAYAAN');
            $sheet->mergeCells('C3:H3')->setCellValue('C3', 'UPT SMA NEGERI 1 CIRUAS');
            $sheet->mergeCells('C4:H4')->setCellValue('C4', 'Jalan Raya Jakarta Km 9,5 Serang Telp. 280043');
            $sheet->mergeCells('C5:H5')->setCellValue('C5', 'Web: www.sman1cir.sch.id | Email: ciruas@sman1cir.sch.id');

            $sheet->mergeCells('A7:H7')->setCellValue(
                'A7',
                'LAPORAN HASIL UJIAN'
            );

            $sheet->mergeCells('A8:H8')->setCellValue(
                'A8',
                'Ujian: ' . $ujian->nama_ujian .
                    ' | Mapel: ' . ($ujian->mataPelajaran->nama_mapel ?? '-') .
                    ' | Kelas: ' . $kelas->nama_kelas
            );

            /*
        |--------------------------------------------------------------------------
        | STYLE KOP
        |--------------------------------------------------------------------------
        */
            $sheet->getStyle('C1:C5')->applyFromArray([
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            $sheet->getStyle('A7')->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);

            $sheet->getStyle('A5:H5')->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                    ],
                ],
            ]);

            /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        */
            $startRow = 10;
            $headers = ['No', 'Nama Siswa', 'Username / NISN', 'Nilai'];

            $sheet->fromArray($headers, null, "A{$startRow}");

            $sheet->getStyle("A{$startRow}:D{$startRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'BDD7EE'],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ]);

            /*
        |--------------------------------------------------------------------------
        | DATA SISWA
        |--------------------------------------------------------------------------
        */
            $siswa = User::query()
                ->select('users.name', 'users.username', 'ua.final_score')
                ->join('ujian_attempt as ua', function ($join) use ($ujianId, $kelas) {
                    $join->on('ua.user_id', '=', 'users.id')
                        ->where('ua.ujian_id', $ujianId)
                        ->where('ua.kelas_id', $kelas->id);
                })
                ->orderByDesc('ua.final_score')
                ->get();

            $row = $startRow + 1;
            $no = 1;

            foreach ($siswa as $item) {
                $sheet->fromArray([
                    $no++,
                    $item->name,
                    $item->username,
                    $item->final_score
                ], null, "A{$row}");

                $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                $row++;
            }

            /*
        |--------------------------------------------------------------------------
        | AUTO WIDTH & PAGE SETUP
        |--------------------------------------------------------------------------
        */
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                ->setPaperSize(PageSetup::PAPERSIZE_LEGAL);

            $sheet->getPageMargins()
                ->setTop(0.5)
                ->setBottom(0.5)
                ->setLeft(0.5)
                ->setRight(0.5);
        }

        /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */
        $filename = 'hasil-ujian-' . str_replace(' ', '-', strtolower($ujian->nama_ujian)) . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
