<?php

namespace App\Services;

use App\Models\TrxProduksiHeader;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportService
{
    /** Kolom tabel data, dipakai untuk header & lebar kolom */
    private const HEADERS = [
        'Tanggal', 'Kode', 'Mesin', 'Pick', 'Panjang (cm)',
        'RPM', 'Efisiensi', 'Jam', 'Jml Mesin',
        'Output Pcs', 'Total Biaya', 'Cost/Pcs', 'Cost/Kodi',
    ];

    private const COL_WIDTHS = [
        12, 10, 16, 8, 12,
        10, 11, 8, 10,
        12, 14, 12, 14,
    ];

    /**
     * Generate spreadsheet riwayat costing.
     *
     * @param  \Illuminate\Support\Collection<int, TrxProduksiHeader> $rows  Data hasil query (sudah include relasi mesin & kalkulasiResult)
     * @param  array $filterInfo  ['tanggal_start' => ..., 'tanggal_end' => ..., 'kode_produksi' => ..., 'mesin_nama' => ...]
     * @return Spreadsheet
     */
    public function generateCostingExport($rows, array $filterInfo = []): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Costing');

        $this->writeJudul($sheet, $filterInfo);
        $this->writeHeaderKolom($sheet, 5);
        $totalBiaya = $this->writeDataRows($sheet, $rows, 6);
        $this->writeTotalRow($sheet, 6 + $rows->count(), $totalBiaya);
        $this->applyColumnWidths($sheet);

        return $spreadsheet;
    }

    /**
     * Baris 1–3: Judul laporan, periode, waktu cetak.
     */
    private function writeJudul($sheet, array $filterInfo): void
    {
        $totalKolom = count(self::HEADERS);
        $lastCol    = $this->columnLetter($totalKolom);

        // Baris 1: Judul besar
        $sheet->setCellValue('A1', 'LAPORAN COSTING PRODUKSI PER MESIN');
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E7FF'); // indigo-100
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Baris 2: Periode + filter
        $periode = $this->formatPeriode($filterInfo);
        $sheet->setCellValue('A2', "Periode: {$periode}");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Baris 3: Waktu cetak
        $sheet->setCellValue('A3', 'Dicetak: ' . Carbon::now()->translatedFormat('d F Y H:i') . ' WIB');
        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->getStyle('A3')->getFont()->setSize(9)->getColor()->setRGB('888888');
    }

    /**
     * Format teks periode + filter aktif untuk ditampilkan di baris 2.
     */
    private function formatPeriode(array $filterInfo): string
    {
        $start = isset($filterInfo['tanggal_start'])
            ? Carbon::parse($filterInfo['tanggal_start'])->translatedFormat('d F Y')
            : 'Semua tanggal';
        $end = isset($filterInfo['tanggal_end'])
            ? Carbon::parse($filterInfo['tanggal_end'])->translatedFormat('d F Y')
            : 'sekarang';

        $tambahan = [];
        if (!empty($filterInfo['kode_produksi'])) {
            $tambahan[] = 'Kode: ' . $filterInfo['kode_produksi'];
        }
        if (!empty($filterInfo['mesin_nama'])) {
            $tambahan[] = 'Mesin: ' . $filterInfo['mesin_nama'];
        }

        $teks = "{$start} — {$end}";
        if (!empty($tambahan)) {
            $teks .= ' | ' . implode(', ', $tambahan);
        }

        return $teks;
    }

    /**
     * Tulis baris header kolom dengan styling bold + background abu-abu.
     */
    private function writeHeaderKolom($sheet, int $row): void
    {
        foreach (self::HEADERS as $i => $label) {
            $col = $this->columnLetter($i + 1);
            $sheet->setCellValue("{$col}{$row}", $label);
        }

        $lastCol = $this->columnLetter(count(self::HEADERS));
        $range   = "A{$row}:{$lastCol}{$row}";

        $sheet->getStyle($range)->getFont()->setBold(true);
        $sheet->getStyle($range)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F3F4F6'); // gray-100
        $sheet->getStyle($range)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($range)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setRGB('D1D5DB');
    }

    /**
     * Tulis data baris demi baris. Return total biaya (untuk baris total).
     */
    private function writeDataRows($sheet, $rows, int $startRow): float
    {
        $totalBiaya = 0.0;
        $row        = $startRow;

        foreach ($rows as $r) {
            $hasil = $r->kalkulasiResult;

            $sheet->setCellValue("A{$row}", $r->tanggal->format('d/m/Y'));
            $sheet->setCellValue("B{$row}", $r->kode_produksi);
            $sheet->setCellValue("C{$row}", $r->mesin?->nama_mesin ?? '—');
            $sheet->setCellValue("D{$row}", $r->pick);
            $sheet->setCellValue("E{$row}", (float) $r->panjang_pcs);
            $sheet->setCellValue("F{$row}", (float) $r->rpm_aktual);
            $sheet->setCellValue("G{$row}", round($r->efisiensi * 100, 2) . '%');
            $sheet->setCellValue("H{$row}", (float) $r->jam_kerja);
            $sheet->setCellValue("I{$row}", $r->jumlah_mesin);
            $sheet->setCellValue("J{$row}", $hasil?->output_riil_pcs ? round($hasil->output_riil_pcs, 2) : 0);
            $sheet->setCellValue("K{$row}", $hasil?->total_biaya_hari ?? 0);
            $sheet->setCellValue("L{$row}", $hasil?->cost_per_pcs ?? 0);
            $sheet->setCellValue("M{$row}", $hasil?->cost_per_kodi ?? 0);

            // Format angka kolom Rupiah (K, L, M) dengan pemisah ribuan
            $sheet->getStyle("K{$row}:M{$row}")
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            $sheet->getStyle("D{$row}:J{$row}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("K{$row}:M{$row}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Border tipis tiap baris
            $sheet->getStyle("A{$row}:M{$row}")->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()->setRGB('E5E7EB');

            $totalBiaya += (float) ($hasil?->total_biaya_hari ?? 0);
            $row++;
        }

        return $totalBiaya;
    }

    /**
     * Baris total — SUM kolom Total Biaya saja (kolom K).
     * Cost/Pcs dan Cost/Kodi tidak di-SUM karena tidak bermakna secara akuntansi.
     */
    private function writeTotalRow($sheet, int $row, float $totalBiaya): void
    {
        $sheet->setCellValue("J{$row}", 'TOTAL');
        $sheet->setCellValue("K{$row}", $totalBiaya);
        $sheet->setCellValue("L{$row}", '—');
        $sheet->setCellValue("M{$row}", '—');

        $sheet->getStyle("K{$row}")->getNumberFormat()->setFormatCode('#,##0');

        $range = "J{$row}:M{$row}";
        $sheet->getStyle($range)->getFont()->setBold(true);
        $sheet->getStyle($range)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FEF3C7'); // amber-100
        $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("K{$row}:M{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }

    /**
     * Atur lebar kolom A–M sesuai konfigurasi.
     */
    private function applyColumnWidths($sheet): void
    {
        foreach (self::COL_WIDTHS as $i => $width) {
            $col = $this->columnLetter($i + 1);
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

    /**
     * Konversi index kolom (1-based) ke huruf kolom Excel (A, B, ..., M).
     */
    private function columnLetter(int $index): string
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
    }

    /**
     * Stream spreadsheet langsung sebagai file download — tanpa simpan ke disk.
     */
    public function streamDownload(Spreadsheet $spreadsheet, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}