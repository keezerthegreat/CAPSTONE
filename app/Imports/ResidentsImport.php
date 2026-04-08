<?php

namespace App\Imports;

use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ResidentsImport implements WithMultipleSheets
{
    public DataSheetImport $dataSheet;

    public function __construct()
    {
        $this->dataSheet = new DataSheetImport;
    }

    public function sheets(): array
    {
        return ['DATA' => $this->dataSheet];
    }
}

class DataSheetImport implements SkipsEmptyRows, ToModel, WithCalculatedFormulas, WithEvents, WithStartRow
{
    public int $imported = 0;

    public int $updated = 0;

    public int $skipped = 0;

    public int $duplicates = 0;

    private ?Carbon $submissionDate = null;

    /** @var int[] */
    private array $touchedHouseholdIds = [];

    public function startRow(): int
    {
        return 10;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $raw = $event->sheet->getDelegate()->getCell('A4')->getValue();
                if ($raw) {
                    try {
                        $this->submissionDate = is_numeric($raw)
                            ? Carbon::instance(ExcelDate::excelToDateTimeObject((float) $raw))
                            : Carbon::parse($raw);
                    } catch (\Exception) {
                        $this->submissionDate = null;
                    }
                }
            },
            AfterSheet::class => function (AfterSheet $event) {
                $this->processHouseholdsAndFamilies();
            },
        ];
    }

    public function model(array $row): ?Resident
    {
        $lastName = $this->val($row, 5); // col F
        $firstName = $this->val($row, 6); // col G

        if (! $lastName || ! $firstName) {
            $this->skipped++;

            return null;
        }

        $upperCheck = strtoupper($lastName.$firstName);
        if (str_contains($upperCheck, 'LACKING')
            || str_contains($upperCheck, 'NOTED BY')
            || str_contains($upperCheck, 'ENTER THIS')
            || str_contains($upperCheck, 'LAST NAME')
            || str_contains($upperCheck, 'VACANT')
            || $upperCheck === 'MALEFEMALE'
            || str_contains($upperCheck, 'TOTAL')) {
            $this->skipped++;

            return null;
        }

        $middleName = $this->val($row, 7); // col H
        $hhNumber = $this->val($row, 1); // col B
        $hhRank = strtoupper($this->val($row, 2) ?? ''); // col C
        $familyRole = match ($hhRank) {
            'HEAD' => 'head',
            'MEMBER' => 'member',
            default => null,
        };

        $gender = match (strtoupper($this->val($row, 13) ?? '')) {
            'M' => 'Male',
            'F' => 'Female',
            default => null,
        };

        $sector = strtolower(trim($this->val($row, 17) ?? ''));
        $isLaborForce = $sector === 'a';
        $isUnemployed = $sector === 'b';
        $isOfw = $sector === 'c';
        $isPwd = $sector === 'd';
        $isSoloParent = $sector === 'e';
        $isIndigenous = $sector === 'f';
        $isOutOfSchoolChild = $sector === 'g';
        $isOutOfSchoolYouth = $sector === 'h';
        $isStudent = $sector === 'i';

        $birthdateRaw = $row[11] ?? null;
        $birthdate = $this->parseDate($birthdateRaw);
        $age = $this->calcAge($birthdateRaw, $this->intVal($row, 12));
        $educLevel = $this->val($row, 22) ?? $this->val($row, 23);
        $address = $this->titleCase($this->val($row, 9)); // col J: Purok/Sitio

        // ── Household auto-linking ──────────────────────────────────────────
        $householdId = null;
        if ($hhNumber) {
            if ($familyRole === 'head') {
                $household = Household::withTrashed()->where('household_number', $hhNumber)->first();
                if (! $household) {
                    $household = Household::create([
                        'household_number' => $hhNumber,
                        'head_last_name' => $this->titleCase($lastName),
                        'head_first_name' => $this->titleCase($firstName),
                        'sitio' => $this->titleCase($this->val($row, 9)) ?? 'Unknown',
                        'street' => $this->titleCase($this->val($row, 7)),
                        'barangay' => 'Cogon',
                        'city' => 'Ormoc City',
                        'province' => 'Leyte',
                    ]);
                } elseif ($household->trashed()) {
                    $household->restore();
                }
            } else {
                $household = Household::withTrashed()->where('household_number', $hhNumber)->first();
                if ($household?->trashed()) {
                    $household->restore();
                }
            }

            if ($household) {
                $householdId = $household->id;
                if (! in_array($household->id, $this->touchedHouseholdIds)) {
                    $this->touchedHouseholdIds[] = $household->id;
                }
            }
        }
        // ───────────────────────────────────────────────────────────────────

        $philsysNumber = $this->val($row, 18); // col S

        // Primary match: exact first name + last name + birthdate
        $existingResident = Resident::whereRaw('LOWER(first_name) = ?', [strtolower($firstName)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($lastName)])
            ->where('birthdate', $birthdate)
            ->first();

        // Fallback: PhilSys number match (catches name corrections in the spreadsheet)
        if (! $existingResident && $philsysNumber) {
            $existingResident = Resident::whereRaw('LOWER(philsys_number) = ?', [strtolower($philsysNumber)])
                ->first();
        }

        if ($existingResident) {
            // If the spreadsheet submission date is newer than the last system update, apply changes
            if ($this->submissionDate && $this->submissionDate->gt($existingResident->updated_at)) {
                $existingResident->update([
                    // Include name fields — allows corrections when matched via PhilSys fallback
                    'first_name' => $this->titleCase($firstName) ?? $existingResident->first_name,
                    'last_name' => $this->titleCase($lastName) ?? $existingResident->last_name,
                    'middle_name' => $this->titleCase($middleName) ?? $existingResident->middle_name,
                    'civil_status' => $this->titleCase($this->val($row, 14)) ?? $existingResident->civil_status,
                    'nationality' => $this->titleCase($this->val($row, 15)) ?: $existingResident->nationality,
                    'occupation' => $this->titleCase($this->val($row, 16)),
                    'philsys_number' => $this->val($row, 18) ?? $existingResident->philsys_number,
                    'religion' => $this->titleCase($this->val($row, 19)) ?? $existingResident->religion,
                    'contact_number' => $this->val($row, 20) ?? $existingResident->contact_number,
                    'email' => $this->val($row, 21) ?? $existingResident->email,
                    'education_level' => $this->titleCase($educLevel) ?? $existingResident->education_level,
                    'address' => $address ?? $existingResident->address,
                    'is_pwd' => $isPwd,
                    'is_senior' => $age !== null && $age >= 60,
                    'is_solo_parent' => $isSoloParent,
                    'is_labor_force' => $isLaborForce,
                    'is_unemployed' => $isUnemployed,
                    'is_ofw' => $isOfw,
                    'is_indigenous' => $isIndigenous,
                    'is_out_of_school_child' => $isOutOfSchoolChild,
                    'is_out_of_school_youth' => $isOutOfSchoolYouth,
                    'is_student' => $isStudent,
                    'household_id' => $householdId ?? $existingResident->household_id,
                    'family_role' => $familyRole ?? $existingResident->family_role,
                ]);
                $this->updated++;
            } else {
                // Spreadsheet is older or same — only fill in blanks, never overwrite
                $updates = [];
                if ($householdId && ! $existingResident->household_id) {
                    $updates['household_id'] = $householdId;
                }
                if ($familyRole && ! $existingResident->family_role) {
                    $updates['family_role'] = $familyRole;
                }
                if ($updates) {
                    $existingResident->update($updates);
                }
                $this->duplicates++;
                $this->skipped++;
            }

            return null;
        }

        $this->imported++;

        return new Resident([
            'last_name' => $this->titleCase($lastName),
            'first_name' => $this->titleCase($firstName),
            'middle_name' => $this->titleCase($middleName),
            'gender' => $gender,
            'birthdate' => $birthdate,
            'age' => $age,
            'civil_status' => $this->titleCase($this->val($row, 14)),
            'nationality' => $this->titleCase($this->val($row, 15)) ?: 'Filipino',
            'occupation' => $this->titleCase($this->val($row, 16)),
            'philsys_number' => $this->val($row, 18),
            'religion' => $this->titleCase($this->val($row, 19)),
            'contact_number' => $this->val($row, 20),
            'email' => $this->val($row, 21),
            'education_level' => $this->titleCase($educLevel),
            'address' => $address,
            'city' => 'Ormoc City',
            'barangay' => 'Cogon',
            'province' => 'Leyte',
            'is_pwd' => $isPwd,
            'is_senior' => $age !== null && $age >= 60,
            'is_voter' => false,
            'is_solo_parent' => $isSoloParent,
            'is_labor_force' => $isLaborForce,
            'is_unemployed' => $isUnemployed,
            'is_ofw' => $isOfw,
            'is_indigenous' => $isIndigenous,
            'is_out_of_school_child' => $isOutOfSchoolChild,
            'is_out_of_school_youth' => $isOutOfSchoolYouth,
            'is_student' => $isStudent,
            'family_role' => $familyRole,
            'household_id' => $householdId,
            'status' => 'approved',
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Runs after all rows are saved. Updates household heads/counts and auto-creates families.
     */
    private function processHouseholdsAndFamilies(): void
    {
        foreach ($this->touchedHouseholdIds as $hhId) {
            $hh = Household::find($hhId);
            if (! $hh) {
                continue;
            }

            $residents = Resident::where('household_id', $hhId)->get();

            // Update household head and member count
            $hhHead = $residents->firstWhere('family_role', 'head');
            $hh->update([
                'head_resident_id' => $hhHead?->id,
                'member_count' => $residents->count(),
            ]);

            // Clear old families for this household and rebuild by last name
            Family::where('household_id', $hhId)->forceDelete();

            // Group residents by last name — one family per surname
            foreach ($residents->groupBy('last_name') as $lastName => $members) {
                $head = $members->firstWhere('family_role', 'head') ?? $members->first();
                $family = Family::create([
                    'family_name' => $lastName.' Family',
                    'head_resident_id' => $head->id,
                    'head_last_name' => $head->last_name,
                    'head_first_name' => $head->first_name,
                    'head_middle_name' => $head->middle_name,
                    'household_id' => $hhId,
                    'member_count' => $members->count(),
                ]);
                Resident::whereIn('id', $members->pluck('id'))->update(['family_id' => $family->id]);
            }
        }

        // Group residents with no household into families by last name
        $unassigned = Resident::whereNull('household_id')->whereNull('family_id')->get();

        foreach ($unassigned->groupBy('last_name') as $lastName => $members) {
            $existingFamily = Family::whereNull('household_id')
                ->where('family_name', $lastName.' Family')
                ->first();

            if ($existingFamily) {
                Resident::whereIn('id', $members->pluck('id'))->update(['family_id' => $existingFamily->id]);
                $existingFamily->increment('member_count', $members->count());
            } else {
                $head = $members->firstWhere('family_role', 'head') ?? $members->first();
                $family = Family::create([
                    'family_name' => $lastName.' Family',
                    'head_resident_id' => $head->id,
                    'head_last_name' => $head->last_name,
                    'head_first_name' => $head->first_name,
                    'head_middle_name' => $head->middle_name,
                    'household_id' => null,
                    'member_count' => $members->count(),
                ]);
                Resident::whereIn('id', $members->pluck('id'))->update(['family_id' => $family->id]);
            }
        }
    }

    private function titleCase(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return mb_convert_case(mb_strtolower(trim($value), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    private function val(array $row, int $index): ?string
    {
        $v = $row[$index] ?? null;
        if ($v === null) {
            return null;
        }
        if ($v instanceof \DateTime) {
            return null;
        }
        $s = trim((string) $v);
        if ($s === '' || str_starts_with($s, '=') || strtolower($s) === 'n/a') {
            return null;
        }

        return $s;
    }

    private function intVal(array $row, int $index): ?int
    {
        $v = $row[$index] ?? null;
        if ($v === null) {
            return null;
        }
        $i = (int) $v;

        return $i > 0 ? $i : null;
    }

    private function calcAge(mixed $birthdateRaw, ?int $excelAge): ?int
    {
        if ($excelAge && $excelAge > 0) {
            return $excelAge;
        }
        if (! $birthdateRaw) {
            return null;
        }
        try {
            if ($birthdateRaw instanceof \DateTime) {
                return Carbon::instance($birthdateRaw)->age;
            }
            if (is_numeric($birthdateRaw)) {
                return Carbon::instance(
                    Date::excelToDateTimeObject((float) floor((float) $birthdateRaw))
                )->age;
            }

            return Carbon::parse($birthdateRaw)->age;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseDate(mixed $value): ?string
    {
        if (! $value) {
            return null;
        }
        try {
            if ($value instanceof \DateTime) {
                return Carbon::instance($value)->format('Y-m-d');
            }
            if (is_numeric($value)) {
                // Floor to strip the time-of-day fraction and avoid timezone off-by-one
                return Date::excelToDateTimeObject((float) floor((float) $value))
                    ->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
