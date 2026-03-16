<?php

namespace App\Imports;

use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ResidentsImport implements WithMultipleSheets
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;

    public function sheets(): array
    {
        $dataSheet = new DataSheetImport();
        return ['DATA' => $dataSheet];
    }

    // Proxy counts from the inner sheet after import
    public function getImported(): int { return 0; }
}

class DataSheetImport implements SkipsEmptyRows, ToModel, WithStartRow, WithCalculatedFormulas
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;

    private array $touchedHouseholdIds = [];

    public function startRow(): int
    {
        return 10;
    }

    public function model(array $row): ?Resident
    {
        $lastName  = $this->val($row, 5);
        $firstName = $this->val($row, 6);

        if (! $lastName || ! $firstName) {
            $this->skipped++;
            return null;
        }

        $upperCheck = strtoupper($lastName . $firstName);
        if (str_contains($upperCheck, 'LACKING')
            || str_contains($upperCheck, 'NOTED BY')
            || str_contains($upperCheck, 'ENTER THIS')
            || str_contains($upperCheck, 'LAST NAME')
            || $upperCheck === 'MALEFEMALE'
            || str_contains($upperCheck, 'TOTAL')) {
            $this->skipped++;
            return null;
        }

        $middleName = $this->val($row, 7);
        $hhNumber   = $this->val($row, 1);
        $hhRank     = strtoupper($this->val($row, 2) ?? '');
        $familyRole = match ($hhRank) {
            'HEAD'   => 'head',
            'MEMBER' => 'member',
            default  => null,
        };

        $gender = match (strtoupper($this->val($row, 13) ?? '')) {
            'M' => 'Male',
            'F' => 'Female',
            default => null,
        };

        $sector = strtolower(trim($this->val($row, 17) ?? ''));
        $isPwd  = $sector === 'd';

        $birthdateRaw = $row[11] ?? null;
        $birthdate    = $this->parseDate($birthdateRaw);
        $age          = $this->calcAge($birthdateRaw, $this->intVal($row, 12));
        $educLevel    = $this->val($row, 22) ?? $this->val($row, 23);
        $address      = $this->titleCase($this->val($row, 9));

        // ── Household auto-linking ──
        $householdId = null;
        if ($hhNumber) {
            if ($familyRole === 'head') {
                $household = Household::where('household_number', $hhNumber)->first();
                if (! $household) {
                    try {
                        $household = Household::create([
                            'household_number' => $hhNumber,
                            'head_last_name'   => $this->titleCase($lastName),
                            'head_first_name'  => $this->titleCase($firstName),
                            'sitio'            => $this->titleCase($this->val($row, 9)) ?? 'Unknown',
                            'barangay'         => 'Cogon',
                            'city'             => 'Ormoc City',
                            'province'         => 'Leyte',
                        ]);
                    } catch (\Exception $e) {
                        $household = Household::where('household_number', $hhNumber)->first();
                    }
                }
            } else {
                $household = Household::where('household_number', $hhNumber)->first();
            }

            if ($household) {
                $householdId = $household->id;
                if (! in_array($household->id, $this->touchedHouseholdIds)) {
                    $this->touchedHouseholdIds[] = $household->id;
                }
            }
        }

        // Skip duplicate
        $duplicate = Resident::whereRaw('LOWER(first_name) = ?', [strtolower($firstName)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($lastName)])
            ->where('birthdate', $birthdate)
            ->exists();

        if ($duplicate) {
            $this->duplicates++;
            $this->skipped++;
            return null;
        }

        $this->imported++;

        $resident = new Resident([
            'last_name'       => $this->titleCase($lastName),
            'first_name'      => $this->titleCase($firstName),
            'middle_name'     => $this->titleCase($middleName),
            'gender'          => $gender,
            'birthdate'       => $birthdate,
            'age'             => $age,
            'civil_status'    => $this->titleCase($this->val($row, 14)),
            'nationality'     => $this->titleCase($this->val($row, 15)) ?: 'Filipino',
            'occupation'      => $this->titleCase($this->val($row, 16)),
            'philsys_number'  => $this->val($row, 18),
            'religion'        => $this->titleCase($this->val($row, 19)),
            'contact_number'  => $this->val($row, 20),
            'email'           => $this->val($row, 21),
            'education_level' => $this->titleCase($educLevel),
            'address'         => $address,
            'city'            => 'Ormoc City',
            'barangay'        => 'Cogon',
            'province'        => 'Leyte',
            'is_pwd'          => $isPwd,
            'is_senior'       => $age !== null && $age >= 60,
            'is_voter'        => false,
            'family_role'     => $familyRole,
            'household_id'    => $householdId,
            'status'          => 'approved',
        ]);

        return $resident;
    }

    /**
     * Called automatically by Laravel Excel after all rows are processed.
     * We use this to update household heads, member counts, and auto-create families.
     */
    public function __destruct()
    {
        if (empty($this->touchedHouseholdIds)) return;

        foreach ($this->touchedHouseholdIds as $hhId) {
            $hh = Household::find($hhId);
            if (! $hh) continue;

            $head = Resident::where('household_id', $hhId)
                ->where('family_role', 'head')
                ->first();

            $hh->update([
                'head_resident_id' => $head?->id,
                'member_count'     => Resident::where('household_id', $hhId)->count(),
            ]);

            $existingFamily = Family::where('household_id', $hhId)->first();
            if (! $existingFamily && $head) {
                $family = Family::create([
                    'family_name'      => $head->last_name . ' Family',
                    'head_resident_id' => $head->id,
                    'head_last_name'   => $head->last_name,
                    'head_first_name'  => $head->first_name,
                    'head_middle_name' => $head->middle_name,
                    'household_id'     => $hhId,
                    'member_count'     => Resident::where('household_id', $hhId)->count(),
                ]);
                Resident::where('household_id', $hhId)->update(['family_id' => $family->id]);
            }
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function titleCase(?string $value): ?string
    {
        if (! $value) return null;
        return ucwords(strtolower(trim($value)));
    }

    private function val(array $row, int $index): ?string
    {
        $v = $row[$index] ?? null;
        if ($v === null) return null;
        if ($v instanceof \DateTime) return null;
        $s = trim((string) $v);
        if ($s === '' || str_starts_with($s, '=')) return null;
        return $s;
    }

    private function intVal(array $row, int $index): ?int
    {
        $v = $row[$index] ?? null;
        if ($v === null) return null;
        $i = (int) $v;
        return $i > 0 ? $i : null;
    }

    private function calcAge(mixed $birthdateRaw, ?int $excelAge): ?int
    {
        if ($excelAge && $excelAge > 0) return $excelAge;
        if (! $birthdateRaw) return null;
        try {
            if ($birthdateRaw instanceof \DateTime) {
                return \Carbon\Carbon::instance($birthdateRaw)->age;
            }
            if (is_numeric($birthdateRaw)) {
                return \Carbon\Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $birthdateRaw)
                )->age;
            }
            return \Carbon\Carbon::parse($birthdateRaw)->age;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseDate(mixed $value): ?string
    {
        if (! $value) return null;
        try {
            if ($value instanceof \DateTime) {
                return \Carbon\Carbon::instance($value)->format('Y-m-d');
            }
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)
                    ->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}