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
            'limits',
            'group1_limit',
            'group2_limit',
            'group3_limit',
            'group4_limit',
            'group5_limit'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // UID
            'C' => NumberFormat::FORMAT_TEXT, // phone
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 10,
            'E' => 10,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}