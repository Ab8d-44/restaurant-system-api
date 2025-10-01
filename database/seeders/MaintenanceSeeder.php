<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Maintenance;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Get only Managers
        $managers = Employee::where('position', 'Manager')
            ->pluck('id')
            ->toArray();

        if (empty($managers)) {
            $this->command->info('No managers found. Please seed employees first.');
            return;
        }

        $maintenanceItems = [
            'Kitchen Equipment Repair',
            'Dining Furniture Maintenance',
            'POS System Upgrade',
            'Refrigeration Service',
            'HVAC Maintenance',
            'Lighting System Repair',
            'Plumbing Fix',
            'Safety Equipment Check',
            'Interior Painting',
            'Exterior Maintenance'
        ];

        for ($i = 0; $i < 100; $i++) {
            $price = $faker->numberBetween(100000, 2000000);
            $discount = $faker->boolean(20) ? $faker->numberBetween(5, 15) : null;

            Maintenance::create([
                'maintenance_item' => $faker->randomElement($maintenanceItems),
                'price' => $price,
                'total_price' => $discount ? $price - ($price * $discount / 100) : $price,
                'discount' => $discount,
                'notes' => $faker->optional(0.3)->sentence,
                'employee_id' => $faker->randomElement($managers), // سيختار فقط من المديرين
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}