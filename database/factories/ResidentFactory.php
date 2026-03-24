<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    public function definition(): array
    {
        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres',
            'Castillo', 'Flores', 'Ramos', 'Villanueva', 'Gonzales', 'Diaz', 'Dela Cruz',
            'Aquino', 'Macaraeg', 'Soriano', 'Mercado', 'Lim', 'Manalo', 'Aguilar',
            'Navarro', 'Salazar', 'Pascual', 'Espiritu', 'Estrada', 'Bernardo', 'Valdez', 'Domingo',
        ];

        $maleFirstNames = [
            'Juan', 'Jose', 'Ramon', 'Eduardo', 'Roberto', 'Miguel', 'Antonio', 'Carlos',
            'Fernando', 'Ricardo', 'Emmanuel', 'Leonardo', 'Rodrigo', 'Andres', 'Danilo',
            'Renato', 'Rolando', 'Alfredo', 'Armando', 'Ernesto',
        ];

        $femaleFirstNames = [
            'Maria', 'Ana', 'Rosa', 'Luz', 'Elena', 'Gloria', 'Carmen', 'Corazon', 'Marilou',
            'Lourdes', 'Teresita', 'Josephine', 'Maricel', 'Cristina', 'Rosario',
            'Evelyn', 'Remedios', 'Natividad', 'Felicidad', 'Angelica',
        ];

        $middleNames = [
            'Santos', 'Reyes', 'Cruz', 'Garcia', 'Mendoza', 'Torres', 'Ramos', 'Flores',
            'Diaz', 'Aquino', 'Lim', 'Navarro', 'Salazar', 'Espiritu', 'Bernardo',
            'Valdez', 'Domingo', 'Ocampo', 'Castillo', 'Gonzales',
        ];

        $puroks = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang', 'Jasmin', 'Rosal', 'Sampaguita'];
        $purok  = $this->faker->randomElement($puroks);

        $gender     = $this->faker->randomElement(['Male', 'Female']);
        $firstName  = $gender === 'Male'
            ? $this->faker->randomElement($maleFirstNames)
            : $this->faker->randomElement($femaleFirstNames);

        $birthdate = $this->faker->dateTimeBetween('-80 years', '-15 years')->format('Y-m-d');
        $age       = \Carbon\Carbon::parse($birthdate)->age;
        $isSenior  = $age >= 60;

        $civilStatuses = ['Single', 'Married', 'Widowed', 'Separated', 'Annulled', 'Common Law (Live-in)', 'Divorced'];
        $religions     = [
            'Roman Catholic', 'Islam', 'Iglesia ni Cristo', "Jehovah's Witness",
            'Seventh-Day Adventist Church', 'Baptist Church', 'Born Again Christians',
            'Philippine Independent Church (Aglipayan)', 'United Church of Christ in the Philippines (UCCP)',
            'United Methodist Church', 'Episcopal Church in the Philippines',
            'Ang Dating Daan', 'Bread of Life Ministries', 'Lutheran Church in the Philippines',
        ];
        $occupations   = ['Farmer', 'Vendor', 'Teacher', 'Driver', 'Carpenter', 'Fisherman', 'Nurse', 'Housewife', null];
        $educLevels    = ['Elementary Graduate', 'High School Graduate', 'Vocational', 'College Graduate', 'Post Graduate', null];

        $isVoter      = $age >= 18 && $this->faker->boolean(70);
        $isPwd        = $this->faker->boolean(8);
        $isSoloParent = $this->faker->boolean(5);
        $isLaborForce = $age >= 15 && $age <= 64 && $this->faker->boolean(50);
        $isUnemployed = $isLaborForce && $this->faker->boolean(20);
        $isOfw        = $this->faker->boolean(5);
        $isIndigenous = $this->faker->boolean(4);
        $isOsc        = $age >= 6 && $age <= 11 && $this->faker->boolean(10);
        $isOsy        = $age >= 12 && $age <= 30 && $this->faker->boolean(10);
        $isStudent    = $age >= 6 && $age <= 24 && $this->faker->boolean(30);

        return [
            'last_name'              => $this->faker->randomElement($lastNames),
            'first_name'             => $firstName,
            'middle_name'            => $this->faker->randomElement($middleNames),
            'suffix'                 => $gender === 'Male' ? $this->faker->optional(0.1)->randomElement(['Jr.', 'Sr.', 'II', 'III']) : null,
            'gender'                 => $gender,
            'birthdate'              => $birthdate,
            'age'                    => $age,
            'civil_status'           => $this->faker->randomElement($civilStatuses),
            'nationality'            => 'Filipino',
            'resident_type'          => $this->faker->randomElement(['Permanent', 'Migrant', 'Transient']),
            'religion'               => $this->faker->randomElement($religions),
            'contact_number'         => $this->faker->optional(0.7)->numerify('09#########'),
            'email'                  => $this->faker->optional(0.4)->safeEmail(),
            'philsys_number'         => $this->faker->optional(0.3)->numerify('####-####-####'),
            'province'               => 'Leyte',
            'city'                   => 'Ormoc City',
            'barangay'               => 'Cogon',
            'address'                => $purok,
            'occupation'             => $this->faker->randomElement($occupations),
            'employer'               => $this->faker->optional(0.4)->company(),
            'monthly_income'         => $this->faker->optional(0.6)->randomElement([3000, 5000, 7000, 10000, 15000, 20000, 25000]),
            'education_level'        => $this->faker->randomElement($educLevels),
            'is_senior'              => $isSenior,
            'is_pwd'                 => $isPwd,
            'is_voter'               => $isVoter,
            'is_solo_parent'         => $isSoloParent,
            'is_labor_force'         => $isLaborForce,
            'is_unemployed'          => $isUnemployed,
            'is_ofw'                 => $isOfw,
            'is_indigenous'          => $isIndigenous,
            'is_out_of_school_child' => $isOsc,
            'is_out_of_school_youth' => $isOsy,
            'is_student'             => $isStudent,
            'is_deceased'            => false,
            'status'                 => 'approved',
        ];
    }
}
