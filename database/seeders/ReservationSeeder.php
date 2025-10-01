<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run()
    {
        // Create 50 tables directly without factory
        $tables = [];
        for ($i = 1; $i <= 50; $i++) {
            $table = Table::create([
                'table_number' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'capacity' => $this->faker->numberBetween(2, 12),
                'location' => $this->faker->randomElement(['Indoor', 'Outdoor', 'VIP', 'Roof']),
                'is_active' => true,
                'price_per_hour' => $this->faker->numberBetween(5000, 50000),
                'description' => $this->faker->optional(0.6)->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $tables[] = $table;
        }

        // Get all customers and employees
        $customers = Customer::all();
        $employees = Employee::whereIn('position', ['Waiter', 'Manager'])->get();

        // Create 150 reservations
        for ($i = 0; $i < 150; $i++) {
            $now = Carbon::now();
            $reservationDate = $this->faker->dateTimeBetween('-1 month', '+2 months');
            $isPast = $reservationDate < $now;
            $isApplication = $this->faker->boolean(70); // 70% chance of being application reservation

            // Determine reservation type and relationships
            if ($isApplication) {
                $type = 'Application';
                $status = $this->determineApplicationStatus($reservationDate, $now);
                $customerId = $customers->random()->id;
                $employeeId = null; // No employee for application reservations
            } else {
                $type = 'Locally';
                $status = $isPast ? 'Completed' : 'Confirmed';
                $customerId = null; // No customer for local reservations
                $employeeId = $employees->random()->id;
            }

            // Safe order assignment
            $orderId = null;
            if ($isPast && $this->faker->boolean(60)) {
                $randomOrder = Order::inRandomOrder()->first();
                $orderId = $randomOrder ? $randomOrder->id : null;
            }

            // Create reservation
            $reservation = Reservation::create([
                'reservation_code' => strtoupper($this->faker->unique()->bothify('??##??##')),
                'status' => $status,
                'party_size' => $this->faker->numberBetween(1, 12),
                'type' => $type,
                'special_requests' => $this->faker->optional(0.4)->text(100),
                'customer_id' => $customerId,
                'employee_id' => $employeeId,
                'order_id' => $orderId,
                'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
                'updated_at' => now(),
            ]);

            // Assign tables to reservation
            $this->assignTablesToReservation($reservation, $tables, $reservationDate);
        }
    }

    /**
     * Determine status for application reservations
     */
    private function determineApplicationStatus($reservationDate, $now): string
    {
        $reservationCarbon = Carbon::parse($reservationDate);
        $diffInMinutes = $now->diffInMinutes($reservationCarbon, false);

        if ($diffInMinutes < 0) {
            return 'Completed';
        } elseif ($diffInMinutes <= 5) {
            // Within 5 minutes of reservation time
            return $this->faker->boolean(80) ? 'Confirmed' : 'Cancelled';
        } else {
            return 'Pending';
        }
    }

    /**
     * Assign tables to reservation with time slots
     */
    private function assignTablesToReservation($reservation, $tables, $reservationDate): void
    {
        $tableCount = $reservation->party_size <= 4 ? 1 : ceil($reservation->party_size / 4);

        // Make sure we don't try to select more tables than available
        $tableCount = min($tableCount, count($tables));

        $selectedTables = $this->faker->randomElements($tables, $tableCount);

        foreach ($selectedTables as $table) {
            $startHour = $this->faker->numberBetween(10, 20);
            $startMinute = $this->faker->randomElement([0, 15, 30, 45]);

            $startTime = Carbon::parse($reservationDate->format('Y-m-d'))
                ->setTime($startHour, $startMinute);

            $endTime = (clone $startTime)->addHours($this->faker->numberBetween(1, 4));

            DB::table('reservation_table')->insert([
                'reservation_id' => $reservation->id,
                'table_id' => $table->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }

}
