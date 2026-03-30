<?php

namespace App\Exports;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResidentsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(private readonly Builder $query) {}

    public function title(): string
    {
        return 'Residents';
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Last Name',
            'First Name',
            'Middle Name',
            'Suffix',
            'Sex',
            'Birthdate',
            'Age',
            'Civil Status',
            'Nationality',
            'Religion',
            'Contact Number',
            'Email',
            'PhilSys No.',
            'Address',
            'Barangay',
            'City',
            'Province',
            'Occupation',
            'Employer',
            'Monthly Income',
            'Education Level',
            'Household No.',
            'Senior Citizen',
            'PWD',
            'Voter',
            'Solo Parent',
            'Solo Parent ID No.',
            'Labor Force',
            'Unemployed',
            'OFW',
            'Indigenous',
            'Out-of-School Child',
            'Out-of-School Youth',
            'Student',
            'Deceased',
            'Transferred To',
            'Status',
        ];
    }

    /** @param Resident $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->last_name,
            $row->first_name,
            $row->middle_name,
            $row->suffix,
            $row->gender,
            $row->birthdate,
            $row->age,
            $row->civil_status,
            $row->nationality,
            $row->religion,
            $row->contact_number,
            $row->email,
            $row->philsys_number,
            $row->address,
            $row->barangay,
            $row->city,
            $row->province,
            $row->occupation,
            $row->employer,
            $row->monthly_income,
            $row->education_level,
            $row->household?->household_number,
            $row->is_senior ? 'Yes' : 'No',
            $row->is_pwd ? 'Yes' : 'No',
            $row->is_voter ? 'Yes' : 'No',
            $row->is_solo_parent ? 'Yes' : 'No',
            $row->solo_parent_id_number,
            $row->is_labor_force ? 'Yes' : 'No',
            $row->is_unemployed ? 'Yes' : 'No',
            $row->is_ofw ? 'Yes' : 'No',
            $row->is_indigenous ? 'Yes' : 'No',
            $row->is_out_of_school_child ? 'Yes' : 'No',
            $row->is_out_of_school_youth ? 'Yes' : 'No',
            $row->is_student ? 'Yes' : 'No',
            $row->is_deceased ? 'Yes' : 'No',
            $row->transferred_to,
            ucfirst($row->status),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
            ],
        ];
    }
}
