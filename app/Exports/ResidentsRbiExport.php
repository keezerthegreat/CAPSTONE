<?php

namespace App\Exports;

use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResidentsRbiExport
{
    public function __construct(private readonly Builder $query) {}

    public function download(): StreamedResponse
    {
        $spreadsheet = IOFactory::load(resource_path('exports/rbi_template.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        // Set submission date to today
        $sheet->setCellValue('A4', ExcelDate::PHPToExcel(now()->timestamp));

        // Clear existing data rows (10 onwards)
        $highestRow = $sheet->getHighestRow();
        for ($row = 10; $row <= $highestRow; $row++) {
            foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC'] as $col) {
                $sheet->getCell($col.$row)->setValue('');
            }
        }

        $residents = $this->query->with(['household', 'family'])->get();
        $rowNum = 10;

        foreach ($residents as $i => $resident) {
            $completeName = $this->completeName($resident);

            $sheet->setCellValue('A'.$rowNum, $i + 1);
            $sheet->setCellValue('B'.$rowNum, $resident->household?->household_number ?? '');
            $sheet->setCellValue('C'.$rowNum, strtoupper($resident->family_role ?? ''));
            $sheet->setCellValue('D'.$rowNum, $resident->family_id ? 'F'.$resident->family_id : '');
            $sheet->setCellValue('E'.$rowNum, $completeName);
            $sheet->setCellValue('F'.$rowNum, strtoupper($resident->last_name ?? ''));
            $sheet->setCellValue('G'.$rowNum, strtoupper($resident->first_name ?? ''));
            $sheet->setCellValue('H'.$rowNum, strtoupper($resident->middle_name ?? ''));
            $sheet->setCellValue('I'.$rowNum, strtoupper($resident->suffix ?? ''));
            $sheet->setCellValue('J'.$rowNum, strtoupper($resident->address ?? ''));
            $sheet->setCellValue('K'.$rowNum, strtoupper($resident->place_of_birth ?? ''));

            if ($resident->birthdate) {
                // Use noon UTC to prevent timezone-induced off-by-one day when re-importing
                $excelDate = ExcelDate::PHPToExcel(
                    Carbon::parse($resident->birthdate)->setTime(12, 0, 0)->utc()->timestamp
                );
                $sheet->setCellValue('L'.$rowNum, $excelDate);
                $sheet->getStyle('L'.$rowNum)->getNumberFormat()->setFormatCode('MM/DD/YYYY');
            }

            $sheet->setCellValue('M'.$rowNum, $resident->age ?? '');
            $sheet->setCellValue('N'.$rowNum, match ($resident->gender) {
                'Male' => 'M',
                'Female' => 'F',
                default => '',
            });
            $sheet->setCellValue('O'.$rowNum, strtoupper($resident->civil_status ?? ''));
            $sheet->setCellValue('P'.$rowNum, strtoupper($resident->nationality ?? ''));
            $sheet->setCellValue('Q'.$rowNum, strtoupper($resident->occupation ?? ''));
            $sheet->setCellValue('R'.$rowNum, $this->sectorCode($resident));
            $sheet->setCellValue('S'.$rowNum, $resident->philsys_number ?? '');
            $sheet->setCellValue('T'.$rowNum, strtoupper($resident->religion ?? ''));
            $sheet->setCellValue('U'.$rowNum, $resident->contact_number ?? '');
            $sheet->setCellValue('V'.$rowNum, $resident->email ?? '');
            $sheet->setCellValue('W'.$rowNum, strtoupper($resident->education_level ?? ''));
            $sheet->setCellValue('X'.$rowNum, strtoupper($resident->education_sub_level ?? ''));
            $sheet->setCellValue('AA'.$rowNum, strtoupper($resident->resident_type ?? ''));
            $sheet->setCellValue('AB'.$rowNum, $completeName);

            $rowNum++;
        }

        $filename = 'RBI_COGON_'.now()->format('Y-m-d').'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setPreCalculateFormulas(false);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function completeName(Resident $resident): string
    {
        $parts = [strtoupper($resident->first_name ?? '')];
        if ($resident->middle_name) {
            $parts[] = strtoupper(substr($resident->middle_name, 0, 1)).'.';
        }
        $parts[] = strtoupper($resident->last_name ?? '');
        if ($resident->suffix) {
            $parts[] = strtoupper($resident->suffix);
        }

        return implode(' ', array_filter($parts));
    }

    private function sectorCode(Resident $resident): string
    {
        if ($resident->is_labor_force) {
            return 'a';
        }
        if ($resident->is_unemployed) {
            return 'b';
        }
        if ($resident->is_ofw) {
            return 'c';
        }
        if ($resident->is_pwd) {
            return 'd';
        }
        if ($resident->is_solo_parent) {
            return 'e';
        }
        if ($resident->is_indigenous) {
            return 'f';
        }
        if ($resident->is_out_of_school_child) {
            return 'g';
        }
        if ($resident->is_out_of_school_youth) {
            return 'h';
        }
        if ($resident->is_student) {
            return 'i';
        }

        return '';
    }
}
