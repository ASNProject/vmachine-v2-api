<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CustomerTemplateExport implements 
    WithHeadings,
    WithColumnFormatting,
    WithColumnWidths,
    WithStyles
{
    public function headings(): array
    {
        return [
            'uid',
            'name',
            'phone_number',
            'role_id',
            'limits'
        ];
    }

    // membuat kolom phone_number sebagai TEXT
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    // mengatur lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 10,
            'E' => 10,
        ];
    }

    // membuat header bold
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}