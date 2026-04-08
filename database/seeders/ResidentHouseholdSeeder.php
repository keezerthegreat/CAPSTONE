<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;
use App\Models\ResidentPendingEdit;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResidentHouseholdSeeder extends Seeder
{
    // Purok centers placed inside the Cogon boundary polygon
    // Boundary spans approx lat 11.011–11.031, lng 124.599–124.610
    private array $purokCenters = [
        'Chrysanthemum' => [11.0280, 124.6030],
        'Dahlia'        => [11.0270, 124.6060],
        'Dama de Noche' => [11.0250, 124.6040],
        'Ilang-Ilang'   => [11.0230, 124.6040],
        'Ilang-Ilang 1' => [11.0210, 124.6030],
        'Ilang-Ilang 2' => [11.0210, 124.6060],
        'Jasmin'        => [11.0190, 124.6050],
        'Rosal'         => [11.0160, 124.6040],
        'Sampaguita'    => [11.0140, 124.6060],
    ];

    private array $lastNames = [
        'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres',
        'Castillo', 'Flores', 'Ramos', 'Villanueva', 'Gonzales', 'Diaz', 'Dela Cruz',
        'Aquino', 'Macaraeg', 'Soriano', 'Mercado', 'Lim', 'Manalo', 'Aguilar',
        'Navarro', 'Salazar', 'Pascual', 'Espiritu', 'Estrada', 'Bernardo', 'Valdez', 'Domingo',
        'Hipolito', 'Buenaventura', 'Delos Reyes', 'Macapagal', 'Palma', 'Lucero',
        'Madrigal', 'Perez', 'Alejandro', 'Bacani', 'Umali', 'Chua', 'Ty', 'Go',
        'Tiu', 'Ngo', 'Uy', 'Sy', 'Tan', 'Ong',
    ];

    private array $maleFirstNames = [
        'Juan', 'Jose', 'Ramon', 'Eduardo', 'Roberto', 'Miguel', 'Antonio', 'Carlos',
        'Fernando', 'Ricardo', 'Emmanuel', 'Leonardo', 'Rodrigo', 'Andres', 'Danilo',
        'Renato', 'Rolando', 'Alfredo', 'Armando', 'Ernesto', 'Nestor', 'Victorino',
        'Alejandro', 'Benjamin', 'Cristobal', 'Domingo', 'Enrique', 'Francisco',
        'Gerardo', 'Herminio', 'Ignacio', 'Jaime', 'Kevin', 'Luis', 'Marco',
        'Nathan', 'Oscar', 'Pablo', 'Quirino', 'Rafael', 'Salvador', 'Teodoro',
        'Ulysses', 'Vicente', 'William', 'Xavier', 'Yosef', 'Zachary',
        'Arnel', 'Rodel', 'Ronaldo', 'Rommel', 'Noel', 'Joel', 'Dennis',
        'Mark', 'John', 'Michael', 'Christian', 'Allan', 'Rey', 'Dante',
    ];

    private array $femaleFirstNames = [
        'Maria', 'Ana', 'Rosa', 'Luz', 'Elena', 'Gloria', 'Carmen', 'Corazon', 'Marilou',
        'Lourdes', 'Teresita', 'Josephine', 'Maricel', 'Cristina', 'Rosario',
        'Evelyn', 'Remedios', 'Natividad', 'Felicidad', 'Angelica',
        'Rowena', 'Lorena', 'Vivian', 'Sheryl', 'Gina', 'Lina', 'Irene',
        'Patricia', 'Noemi', 'Hazel', 'April', 'May', 'Joyce', 'Joy',
        'Grace', 'Faith', 'Hope', 'Charity', 'Love', 'Pearl', 'Ruby',
        'Jasmine', 'Rose', 'Lily', 'Daisy', 'Violet', 'Iris', 'Flora',
        'Diana', 'Melissa', 'Jennifer', 'Michelle', 'Nicole', 'Vanessa',
        'Marianne', 'Rosemarie', 'Annalyn', 'Charisse', 'Dianne', 'Elaine',
    ];

    private array $middleNames = [
        'Santos', 'Reyes', 'Cruz', 'Garcia', 'Mendoza', 'Torres', 'Ramos', 'Flores',
        'Diaz', 'Aquino', 'Lim', 'Navarro', 'Salazar', 'Espiritu', 'Bernardo',
        'Valdez', 'Domingo', 'Ocampo', 'Castillo', 'Gonzales', 'Hipolito',
        'Macapagal', 'Lucero', 'Perez', 'Alejandro', 'Dela Cruz', 'Manalo',
    ];

    public function run(): void
    {
        // Wipe existing data
        $this->command->info('Clearing existing data...');
        DB::statement('PRAGMA foreign_keys = OFF');
        ResidentPendingEdit::query()->delete();
        ActivityLog::query()->delete();
        Resident::withTrashed()->forceDelete();
        Family::withTrashed()->forceDelete();
        Household::withTrashed()->forceDelete();
        DB::statement('PRAGMA foreign_keys = ON');

        $faker = Faker::create('en_PH');
        $faker->seed(42);

        $puroks = array_keys($this->purokCenters);
        $religions = [
            'Roman Catholic', 'Iglesia ni Cristo', 'Born Again Christian',
            'Islam', 'Seventh Day Adventist', "Jehovah's Witness", 'Aglipayan',
        ];
        $occupations = [
            'Farmer', 'Vendor', 'Teacher', 'Driver', 'Carpenter', 'Fisherman',
            'Nurse', 'Housewife', 'Security Guard', 'Construction Worker',
            'Electrician', 'Mechanic', 'Store Owner', 'Tricycle Driver',
            'Government Employee', 'OFW', 'Self-Employed',
        ];
        $educLevels = [
            'Elementary Graduate', 'High School Graduate', 'Vocational',
            'College Graduate', 'Post Graduate', 'Elementary Undergraduate',
            'High School Undergraduate', 'College Undergraduate',
        ];
        $resTypes = ['Permanent', 'Migrant', 'Transient'];

        $hhCounter = 1;
        $totalCreated = 0;

        // ~7 households per purok = 63 households × avg 5 members ≈ 315 residents
        $householdsPerPurok = 7;

        foreach ($puroks as $purok) {
            [$centerLat, $centerLng] = $this->purokCenters[$purok];

            for ($h = 0; $h < $householdsPerPurok; $h++) {
                $lastName = $faker->randomElement($this->lastNames);

                // GPS: scatter within ~0.001° (~100m) of purok center
                $lat = $centerLat + ($faker->randomFloat(6, -0.0010, 0.0010));
                $lng = $centerLng + ($faker->randomFloat(6, -0.0010, 0.0010));

                $household = Household::create([
                    'household_number' => sprintf('HH-%04d', $hhCounter),
                    'head_last_name' => $lastName,
                    'head_first_name' => 'TBD',
                    'sitio' => $purok,
                    'barangay' => 'Cogon',
                    'city' => 'Ormoc City',
                    'province' => 'Leyte',
                    'residency_type' => $faker->randomElement($resTypes),
                    'member_count' => 0,
                    'latitude' => round($lat, 6),
                    'longitude' => round($lng, 6),
                ]);

                $family = Family::create([
                    'family_name' => $lastName.' Family',
                    'head_last_name' => $lastName,
                    'head_first_name' => 'TBD',
                    'household_id' => $household->id,
                    'member_count' => 0,
                ]);

                // Determine household size: 3–7 members
                $memberCount = $faker->numberBetween(3, 7);

                // Build age slots: head (adult), then a mix
                $ageSlots = $this->buildAgeSlots($faker, $memberCount);
                $memberData = [];

                foreach ($ageSlots as $idx => $slot) {
                    $isHead = $idx === 0;
                    $gender = $isHead
                        ? 'Male'
                        : ($faker->boolean(50) ? 'Male' : 'Female');

                    $firstName = $gender === 'Male'
                        ? $faker->randomElement($this->maleFirstNames)
                        : $faker->randomElement($this->femaleFirstNames);
                    $middleName = $faker->randomElement($this->middleNames);

                    $birthdate = $this->birthdateForSlot($faker, $slot);
                    $age = Carbon::parse($birthdate)->age;

                    // Sector flags (mutually exclusive priority)
                    [$sector, $flags] = $this->assignSector($faker, $age, $slot, $isHead);

                    $isSenior = $age >= 60;
                    $isVoter = $age >= 18 && $faker->boolean(75);

                    $familyRole = $this->familyRoleForSlot($isHead, $slot, $gender);
                    $civilStatus = $this->civilStatusForSlot($faker, $slot, $isHead);

                    $resident = Resident::create([
                        'last_name' => $lastName,
                        'first_name' => $firstName,
                        'middle_name' => $middleName,
                        'suffix' => ($gender === 'Male' && $faker->boolean(8))
                            ? $faker->randomElement(['Jr.', 'Sr.', 'II', 'III'])
                            : null,
                        'gender' => $gender,
                        'birthdate' => $birthdate,
                        'age' => $age,
                        'civil_status' => $civilStatus,
                        'nationality' => 'Filipino',
                        'resident_type' => $faker->randomElement($resTypes),
                        'religion' => $faker->randomElement($religions),
                        'contact_number' => ($isHead || $faker->boolean(40))
                            ? $faker->numerify('09#########')
                            : null,
                        'email' => ($age >= 18 && $faker->boolean(30))
                            ? $faker->safeEmail()
                            : null,
                        'philsys_number' => $faker->boolean(35)
                            ? $faker->numerify('####-####-####')
                            : null,
                        'province' => 'Leyte',
                        'city' => 'Ormoc City',
                        'barangay' => 'Cogon',
                        'address' => $purok,
                        'place_of_birth' => $faker->randomElement(['Ormoc City', 'Cebu City', 'Manila', 'Tacloban City', 'Leyte']),
                        'occupation' => ($age >= 18 && ! $flags['is_student'])
                            ? $faker->randomElement($occupations)
                            : null,
                        'employer' => $faker->boolean(35) ? $faker->company() : null,
                        'monthly_income' => ($age >= 18)
                            ? $faker->randomElement([3000, 5000, 7000, 8000, 10000, 12000, 15000, 20000, 25000, 30000])
                            : null,
                        'education_level' => $faker->randomElement($educLevels),
                        'is_senior' => $isSenior,
                        'is_pwd' => $flags['is_pwd'],
                        'is_voter' => $isVoter,
                        'is_solo_parent' => $flags['is_solo_parent'],
                        'is_labor_force' => $flags['is_labor_force'],
                        'is_unemployed' => $flags['is_unemployed'],
                        'is_ofw' => $flags['is_ofw'],
                        'is_indigenous' => $flags['is_indigenous'],
                        'is_out_of_school_child' => $flags['is_out_of_school_child'],
                        'is_out_of_school_youth' => $flags['is_out_of_school_youth'],
                        'is_student' => $flags['is_student'],
                        'is_deceased' => false,
                        'household_id' => $household->id,
                        'family_id' => $family->id,
                        'family_role' => $familyRole,
                        'status' => 'approved',
                    ]);

                    $memberData[] = $resident;

                    if ($isHead) {
                        $household->update([
                            'head_resident_id' => $resident->id,
                            'head_first_name' => $resident->first_name,
                            'head_last_name' => $resident->last_name,
                            'head_middle_name' => $resident->middle_name,
                        ]);
                        $family->update([
                            'head_resident_id' => $resident->id,
                            'head_first_name' => $resident->first_name,
                            'head_last_name' => $resident->last_name,
                            'head_middle_name' => $resident->middle_name,
                            'head_role' => 'Head',
                        ]);
                    }
                }

                $count = count($memberData);
                $household->update(['member_count' => $count]);
                $family->update(['member_count' => $count]);
                $totalCreated += $count;
                $hhCounter++;
            }
        }

        $this->command->info('Seeded: '.($hhCounter - 1).' households, '.($hhCounter - 1)." families, {$totalCreated} residents.");
    }

    /**
     * Build an array of age slots for a household.
     * Slot types: 'head', 'spouse', 'child', 'young_adult', 'adult', 'senior'
     *
     * @return string[]
     */
    private function buildAgeSlots(Generator $faker, int $memberCount): array
    {
        $slots = ['head'];

        // ~60% chance of a spouse
        if ($memberCount >= 2 && $faker->boolean(60)) {
            $slots[] = 'spouse';
        }

        $remaining = $memberCount - count($slots);

        // Fill with weighted random slots
        $pool = [
            'child' => 30,
            'young_adult' => 20,
            'adult' => 30,
            'senior' => 20,
        ];

        for ($i = 0; $i < $remaining; $i++) {
            $slots[] = $this->weightedRandom($faker, $pool);
        }

        return $slots;
    }

    private function birthdateForSlot(Generator $faker, string $slot): string
    {
        return match ($slot) {
            'head' => $faker->dateTimeBetween('-58 years', '-30 years')->format('Y-m-d'),
            'spouse' => $faker->dateTimeBetween('-56 years', '-25 years')->format('Y-m-d'),
            'child' => $faker->dateTimeBetween('-17 years', '-1 year')->format('Y-m-d'),
            'young_adult' => $faker->dateTimeBetween('-29 years', '-18 years')->format('Y-m-d'),
            'adult' => $faker->dateTimeBetween('-59 years', '-30 years')->format('Y-m-d'),
            'senior' => $faker->dateTimeBetween('-90 years', '-60 years')->format('Y-m-d'),
            default => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        };
    }

    private function familyRoleForSlot(bool $isHead, string $slot, string $gender): string
    {
        if ($isHead) {
            return 'Head';
        }

        return match ($slot) {
            'spouse' => 'Spouse',
            'child' => $gender === 'Male' ? 'Son' : 'Daughter',
            'young_adult' => $gender === 'Male' ? 'Son' : 'Daughter',
            'senior' => 'Parent',
            default => 'Sibling',
        };
    }

    private function civilStatusForSlot(Generator $faker, string $slot, bool $isHead): string
    {
        if ($isHead || $slot === 'spouse') {
            return 'Married';
        }

        if ($slot === 'child') {
            return 'Single';
        }

        if ($slot === 'senior') {
            return $faker->randomElement(['Widowed', 'Married', 'Single']);
        }

        return $faker->randomElement(['Single', 'Married', 'Separated', 'Widowed']);
    }

    /**
     * Assign one primary sector to a resident (mutually exclusive).
     * Returns [sector_name, flags_array].
     *
     * @return array{string, array<string, bool>}
     */
    private function assignSector(Generator $faker, int $age, string $slot, bool $isHead): array
    {
        $flags = [
            'is_pwd' => false,
            'is_solo_parent' => false,
            'is_labor_force' => false,
            'is_unemployed' => false,
            'is_ofw' => false,
            'is_indigenous' => false,
            'is_out_of_school_child' => false,
            'is_out_of_school_youth' => false,
            'is_student' => false,
        ];

        // Out-of-school child: 6-11
        if ($age >= 6 && $age <= 11 && $faker->boolean(12)) {
            $flags['is_out_of_school_child'] = true;

            return ['osc', $flags];
        }

        // Student: 6-24
        if ($age >= 6 && $age <= 24 && $faker->boolean(45)) {
            $flags['is_student'] = true;

            return ['student', $flags];
        }

        // Out-of-school youth: 15-30
        if ($age >= 15 && $age <= 30 && $faker->boolean(8)) {
            $flags['is_out_of_school_youth'] = true;

            return ['osy', $flags];
        }

        // OFW: 20-50
        if ($age >= 20 && $age <= 50 && $faker->boolean(6)) {
            $flags['is_ofw'] = true;

            return ['ofw', $flags];
        }

        // Indigenous: any age
        if ($faker->boolean(4)) {
            $flags['is_indigenous'] = true;

            return ['indigenous', $flags];
        }

        // PWD: any age
        if ($faker->boolean(7)) {
            $flags['is_pwd'] = true;

            return ['pwd', $flags];
        }

        // Solo parent: 20-55
        if ($age >= 20 && $age <= 55 && $faker->boolean(6)) {
            $flags['is_solo_parent'] = true;

            return ['solo_parent', $flags];
        }

        // Working-age: employed labor force OR unemployed (mutually exclusive)
        if ($age >= 15 && $age <= 64) {
            if ($faker->boolean(65)) {
                if ($faker->boolean(18)) {
                    $flags['is_unemployed'] = true;

                    return ['unemployed', $flags];
                }

                $flags['is_labor_force'] = true;

                return ['labor_force', $flags];
            }
        }

        return ['none', $flags];
    }

    /** @param array<string, int> $weights */
    private function weightedRandom(Generator $faker, array $weights): string
    {
        $total = array_sum($weights);
        $rand = $faker->numberBetween(1, $total);
        $cumulative = 0;

        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $key;
            }
        }

        return array_key_first($weights);
    }
}
