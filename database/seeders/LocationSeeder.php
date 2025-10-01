<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Comprehensive list of Damascus regions and sub-regions
        $damascusRegions = [
            // Central Areas
            'Historic Center' => ['Old City', 'Qemariyeh', 'Amara', 'Bab Sharqi', 'Bab Tuma', 'Bab Salam', 'Bab Al-Faraj'],
            'Modern Areas' => ['Abu Rummaneh', 'Al-Malki', 'Al-Salihiyah', 'Al-Rawda', 'Al-Zahra'],

            // Western Areas
            'Western Ghouta' => ['Daraya', 'Muadamiyat al-Sham', 'Al-Qadam', 'Sayyida Zainab', 'Babila'],
            'Al-Mazzeh Area' => ['Mazzeh 86', 'Old Mazzeh', 'New Mazzeh', 'Mazzeh Villas'],

            // Eastern Areas
            'Eastern Ghouta' => ['Douma', 'Harasta', 'Zamalka', 'Al-Rayhan', 'Kafr Batna'],
            'Rural Damascus' => ['Jisreen', 'Qudsaya', 'Al-Hameh', 'Al-Kiswah', 'Sahnaya'],

            // Northern Areas
            'Northern Suburbs' => ['Barzeh', 'Qaboun', 'Tishreen', 'Al-Rehab', 'Al-Duwaila'],
            'Al-Tal Area' => ['Al-Tal City', 'Deir Salman', 'Al-Hussainiyah', 'Al-Bahdaliyah'],

            // Southern Areas
            'Southern Districts' => ['Kafr Souseh', 'Al-Midan', 'Al-Hajar Al-Aswad', 'Al-Thiyabiyah', 'Al-Asali'],
            'Yarmouk Area' => ['Yarmouk Camp', 'Al-Palestine Street', 'Al-Jaouna', 'Al-Tadamon']
        ];

        // Comprehensive street list organized by type
        $mainStreets = [
            'Baghdad Street',
            '30th May Street',
            'Al-Jalaa Street',
            'Al-Thawra Street',
            'Al-Malek Faisal Street',
            'Al-Nasr Street',
            'Al-Hamra Street',
            'Al-Qassaa Street'
        ];

        $historicStreets = [
            'Straight Street',
            'Al-Amin Street',
            'Al-Hamidiyah Street',
            'Bab Sharqi Street',
            'Al-Qaimariyeh Street',
            'Al-Buzuriyah Street',
            'Al-Madina Street',
            'Al-Adawiyeh Street'
        ];

        $residentialStreets = [
            'Ibn Khaldoun Street',
            'Al-Malki Street',
            'Abu Rummaneh Street',
            'Al-Rawda Street',
            'Al-Zahra Street',
            'Al-Fardous Street',
            'Al-Mazzeh Street',
            'Al-Salihiyah Street'
        ];

        $commercialStreets = [
            'Port Said Street',
            'Al-Marjeh Street',
            'Al-Qanawat Street',
            'Al-Baramkeh Street',
            'Al-Shahbandar Street',
            'Al-Souq Street',
            'Al-Takaya Street',
            'Al-Jisr Al-Abyad Street'
        ];

        // Landmarks and special locations
        $landmarks = [
            'Near Umayyad Mosque',
            'Close to Damascus Citadel',
            'Opposite Al-Hamidiyah Souq',
            'Next to Four Seasons Hotel',
            'Near Cham Palace',
            'By Al-Assad Library',
            'Adjacent to Tishreen Park',
            'Facing Al-Jalaa Stadium',
            'Behind Damascus University',
            'Near Dama Rose Hotel',
            'Close to Al-Fayhaa Sports City',
            'Next to Central Bank',
            'Opposite Al-Sham Hotel',
            'Near Hijaz Railway Station',
            'By National Museum'
        ];

        for ($i = 0; $i < 300; $i++) {
            $region = $faker->randomElement(array_keys($damascusRegions));
            $subRegion = $faker->randomElement($damascusRegions[$region]);

            // Determine location type
            $locationType = $faker->randomElement(['street', 'landmark', 'area']);

            $street = null;
            if ($locationType === 'street') {
                $streetType = $faker->randomElement(['main', 'historic', 'residential', 'commercial']);
                switch ($streetType) {
                    case 'main':
                        $street = $faker->randomElement($mainStreets);
                        break;
                    case 'historic':
                        $street = $faker->randomElement($historicStreets);
                        break;
                    case 'residential':
                        $street = $faker->randomElement($residentialStreets);
                        break;
                    case 'commercial':
                        $street = $faker->randomElement($commercialStreets);
                        break;
                }
                $street .= ' ' . $faker->numberBetween(1, 200);
            } elseif ($locationType === 'landmark') {
                $street = $faker->randomElement($landmarks);
            } else {
                $street = $subRegion . ' District';
            }

            Location::create([
                'country' => 'Syria',
                'state' => 'Damascus',
                'city' => 'Damascus',
                'region' => $region,
                'street' => $street,
                'Delivery_time' => '00:' . $faker->numberBetween(10, 40) . ':00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
