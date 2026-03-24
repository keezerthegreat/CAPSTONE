<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResidentHouseholdSeeder extends Seeder
{
    public function run(): void
    {
        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres',
            'Castillo', 'Flores',
        ];

        $maleFirstNames = [
            'Juan', 'Jose', 'Ramon', 'Eduardo', 'Roberto', 'Miguel', 'Antonio', 'Carlos',
            'Fernando', 'Ricardo', 'Emmanuel', 'Leonardo', 'Rodrigo', 'Andres', 'Danilo',
        ];

        $femaleFirstNames = [
            'Maria', 'Ana', 'Rosa', 'Luz', 'Elena', 'Gloria', 'Carmen', 'Corazon', 'Marilou',
            'Lourdes', 'Teresita', 'Josephine', 'Maricel', 'Cristina', 'Rosario',
        ];

        $middleNames = [
            'Santos', 'Reyes', 'Cruz', 'Garcia', 'Mendoza',
            'Torres', 'Ramos', 'Flores', 'Diaz', 'Aquino',
        ];

        $puroks = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang', 'Jasmin', 'Rosal', 'Sampaguita'];
        $civilStatuses = ['Single', 'Married', 'Widowed', 'Separated', 'Annulled', 'Common Law (Live-in)', 'Divorced'];
        $religions     = ['Roman Catholic', 'Iglesia ni Cristo', 'Born Again Christian', 'Islam', 'Seventh Day Adventist'];
        $occupations   = ['Farmer', 'Vendor', 'Teacher', 'Driver', 'Carpenter', 'Fisherman', 'Nurse', 'Housewife', null];
        $educLevels    = ['Elementary Graduate', 'High School Graduate', 'Vocational', 'College Graduate', null];
        $resTypes      = ['Permanent', 'Migrant', 'Transient'];

        $faker = \Faker\Factory::create('en_PH');

        // 10 households, each with a family and 5 members (50 residents total)
        foreach (range(1, 10) as $hhIndex) {
            $lastName = $lastNames[$hhIndex - 1];
            $purok    = $faker->randomElement($puroks);

            // --- Create Household (no head yet) ---
            $household = Household::create([
                'household_number' => sprintf('HH-%04d', $hhIndex),
                'head_last_name'   => $lastName,
                'head_first_name'  => 'TBD',
                'sitio'            => $purok,
                'barangay'         => 'Cogon',
                'city'             => 'Ormoc City',
                'province'         => 'Leyte',
                'residency_type'   => $faker->randomElement($resTypes),
                'member_count'     => 0,
            ]);

            // --- Create Family (no head yet) ---
            $family = Family::create([
                'family_name'       => $lastName . ' Family',
                'head_last_name'    => $lastName,
                'head_first_name'   => 'TBD',
                'household_id'      => $household->id,
                'member_count'      => 0,
            ]);

            $members = [];

            // 5 members per household
            foreach (range(1, 5) as $mIndex) {
                $isHead    = $mIndex === 1;
                $gender    = $isHead ? 'Male' : $faker->randomElement(['Male', 'Female']);
                $firstName = $gender === 'Male'
                    ? $faker->randomElement($maleFirstNames)
                    : $faker->randomElement($femaleFirstNames);
                $middleName = $faker->randomElement($middleNames);

                // Head is 30-55, others 5-75
                $birthdate = $isHead
                    ? $faker->dateTimeBetween('-55 years', '-30 years')->format('Y-m-d')
                    : $faker->dateTimeBetween('-75 years', '-5 years')->format('Y-m-d');

                $age      = Carbon::parse($birthdate)->age;
                $isSenior = $age >= 60;
                $isVoter  = $age >= 18 && $faker->boolean(70);
                $isPwd    = $faker->boolean(8);

                $resident = Resident::create([
                    'last_name'              => $lastName,
                    'first_name'             => $firstName,
                    'middle_name'            => $middleName,
                    'suffix'                 => $gender === 'Male' ? $faker->optional(0.1)->randomElement(['Jr.', 'Sr.', 'II']) : null,
                    'gender'                 => $gender,
                    'birthdate'              => $birthdate,
                    'age'                    => $age,
                    'civil_status'           => $isHead ? 'Married' : $faker->randomElement($civilStatuses),
                    'nationality'            => 'Filipino',
                    'resident_type'          => 'Permanent',
                    'religion'               => $faker->randomElement($religions),
                    'contact_number'         => $isHead ? $faker->numerify('09#########') : $faker->optional(0.5)->numerify('09#########'),
                    'email'                  => $faker->optional(0.3)->safeEmail(),
                    'province'               => 'Leyte',
                    'city'                   => 'Ormoc City',
                    'barangay'               => 'Cogon',
                    'address'                => $purok,
                    'occupation'             => $isHead ? $faker->randomElement(array_filter($occupations)) : $faker->randomElement($occupations),
                    'employer'               => $faker->optional(0.4)->company(),
                    'monthly_income'         => $faker->optional(0.6)->randomElement([3000, 5000, 7000, 10000, 15000, 20000]),
                    'education_level'        => $faker->randomElement($educLevels),
                    'is_senior'              => $isSenior,
                    'is_pwd'                 => $isPwd,
                    'is_voter'               => $isVoter,
                    'is_solo_parent'         => $faker->boolean(5),
                    'is_labor_force'         => $age >= 15 && $age <= 64 && $faker->boolean(50),
                    'is_unemployed'          => $faker->boolean(15),
                    'is_ofw'                 => $faker->boolean(4),
                    'is_indigenous'          => false,
                    'is_out_of_school_child' => $age >= 6 && $age <= 11 && $faker->boolean(10),
                    'is_out_of_school_youth' => $age >= 12 && $age <= 30 && $faker->boolean(10),
                    'is_student'             => $age >= 6 && $age <= 24 && $faker->boolean(30),
                    'is_deceased'            => false,
                    'household_id'           => $household->id,
                    'family_id'              => $family->id,
                    'family_role'            => $isHead ? 'Head' : $faker->randomElement(['Spouse', 'Son', 'Daughter', 'Parent', 'Sibling']),
                    'status'                 => 'approved',
                ]);

                $members[] = $resident;

                // Set household & family head to first member
                if ($isHead) {
                    $household->update([
                        'head_resident_id' => $resident->id,
                        'head_first_name'  => $resident->first_name,
                        'head_last_name'   => $resident->last_name,
                        'head_middle_name' => $resident->middle_name,
                    ]);

                    $family->update([
                        'head_resident_id' => $resident->id,
                        'head_first_name'  => $resident->first_name,
                        'head_last_name'   => $resident->last_name,
                        'head_middle_name' => $resident->middle_name,
                        'head_role'        => 'Head',
                    ]);
                }
            }

            // Update member counts
            $household->update(['member_count' => count($members)]);
            $family->update(['member_count' => count($members)]);
        }

        $this->command->info('Seeded: 10 households, 10 families, 50 residents.');
    }
}
