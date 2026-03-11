<?php

namespace App\Imports;

use App\Models\Household;
use App\Models\Resident;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;

class ResidentsImport implements SkipsEmptyRows, ToModel, WithEvents, WithStartRow
{
    public int $imported = 0;

    public int $skipped = 0;

    public int $duplicates = 0;

    /** Tracks household IDs created/touched during this import for post-processing. */
    private array $touchedHouseholdIds = [];

    // Data starts at row 10 (rows 1-9 are instructions/headers)
    public function startRow(): int
    {
        return 10;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                // Update head_resident_id and member_count for all touched households
                Household::query()
                    ->whereIn('id', $this->touchedHouseholdIds)
                    ->each(function (Household $hh) {
                        $head = Resident::where('household_id', $hh->id)
                            ->where('family_role', 'head')
                            ->first();

                        $hh->update([
                            'head_resident_id' => $head?->id,
                            'member_count' => Resident::where('household_id', $hh->id)->count(),
                        ]);
                    });
            },
        ];
    }

    public function model(array $row): ?Resident
    {
        $lastName = $this->val($row, 5); // col F
        $firstName = $this->val($row, 6); // col G

        // Skip if either name is missing, or row is a LACKING placeholder/instruction row
        if (! $lastName || ! $firstName) {
            $this->skipped++;

            return null;
        }
        if (str_contains(strtoupper($lastName.$firstName), 'LACKING')
            || str_contains(strtoupper($lastName.$firstName), 'NOTED BY')
            || str_contains(strtoupper($lastName.$firstName), 'ENTER THIS')) {
            $this->skipped++;

            return null;
        }

        // Col E (index 4) contains Excel formulas — skip it, no middle name available
        $middleName = null;

        // Gender: M → Male, F → Female
        $gender = match (strtoupper($this->val($row, 13) ?? '')) {
            'M' => 'Male',
            'F' => 'Female',
            default => null,
        };

        // Sector codes: d=PWD, others stored as occupation context
        $sector = strtolower(trim($this->val($row, 17) ?? ''));
        $isPwd = $sector === 'd';

        // Family role from HH Rank
        $hhRank = strtoupper($this->val($row, 2) ?? '');
        $familyRole = match ($hhRank) {
            'HEAD' => 'head',
            'MEMBER' => 'member',
            default => null,
        };

        $age = $this->calcAge($this->val($row, 11), $this->intVal($row, 12));

        // ── Household auto-linking ────────────────────────────────────────────
        $householdId = null;
        $hhNumber = $this->val($row, 1); // col B: household number (e.g. "0001")

        if ($hhNumber) {
            if ($familyRole === 'head') {
                $household = Household::firstOrCreate(
                    ['household_number' => $hhNumber],
                    [
                        'head_last_name' => $this->titleCase($lastName),
                        'head_first_name' => $this->titleCase($firstName),
                        'sitio' => $this->titleCase($this->val($row, 9)) ?? 'Unknown', // col J
                        'street' => $this->titleCase($this->val($row, 7)),              // col H
                        'barangay' => 'Cogon',
                        'city' => 'Ormoc City',
                        'province' => 'Leyte',
                    ]
                );
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
        // ─────────────────────────────────────────────────────────────────────

        // Skip duplicate: same name + birthdate already exists
        $birthdate = $this->parseDate($this->val($row, 11));
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

        return new Resident([
            'last_name' => $this->titleCase($lastName),
            'first_name' => $this->titleCase($firstName),
            'middle_name' => $middleName,
            'gender' => $gender,
            'birthdate' => $this->parseDate($this->val($row, 11)),   // col L
            'age' => $age,
            'civil_status' => $this->titleCase($this->val($row, 14)),  // col O
            'nationality' => $this->titleCase($this->val($row, 15)) ?: 'Filipino', // col P
            'occupation' => $this->titleCase($this->val($row, 16)),    // col Q
            'philsys_number' => $this->val($row, 18),                  // col S
            'religion' => $this->titleCase($this->val($row, 19)),      // col T
            'contact_number' => $this->val($row, 20),                  // col U
            'email' => $this->val($row, 21),                           // col V
            'education_level' => $this->titleCase($this->val($row, 22)), // col W
            'address' => $this->titleCase(implode(' ', array_filter([
                $this->val($row, 9),   // col J: Purok name
                $this->val($row, 7),   // col H: street/sub-address
            ]))),
            'city' => 'Ormoc City',
            'barangay' => 'Cogon',
            'province' => 'Leyte',
            'is_pwd' => $isPwd,
            'is_senior' => $age !== null && $age >= 60,
            'is_voter' => false,
            'family_role' => $familyRole,
            'household_id' => $householdId,
            'status' => 'approved',
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function titleCase(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return ucwords(strtolower(trim($value)));
    }

    private function val(array $row, int $index): ?string
    {
        $v = $row[$index] ?? null;
        if ($v === null) {
            return null;
        }
        $s = trim((string) $v);
        // Reject unevaluated formula strings
        if ($s === '' || str_starts_with($s, '=')) {
            return null;
        }

        return $s;
    }

    private function intVal(array $row, int $index): ?int
    {
        $v = $this->val($row, $index);
        if ($v === null) {
            return null;
        }
        $i = (int) $v;

        return $i > 0 ? $i : null; // treat 0 as null (formula fallback)
    }

    /**
     * Use col M age if valid (>0), otherwise calculate from birthdate.
     */
    private function calcAge(?string $birthdateStr, ?int $excelAge): ?int
    {
        if ($excelAge && $excelAge > 0) {
            return $excelAge;
        }
        if (! $birthdateStr) {
            return null;
        }
        try {
            // Handle Excel serial date numbers (same logic as parseDate)
            if (is_numeric($birthdateStr)) {
                $bd = \Carbon\Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $birthdateStr)
                );
            } else {
                $bd = \Carbon\Carbon::parse($birthdateStr);
            }

            return $bd->age >= 0 ? $bd->age : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseDate(?string $value): ?string
    {
        if (! $value) {
            return null;
        }
        try {
            // Handle Excel serial date numbers
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
