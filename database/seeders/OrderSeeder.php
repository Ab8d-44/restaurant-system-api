<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Meal;
use App\Models\Offer;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Reservation;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $now = Carbon::now();

        // الحصول على البيانات المطلوبة
        $customers = Customer::all();
        $employees = Employee::all();
        $reservations = Reservation::all();
        $locations = Location::all();
        $meals = Meal::all();
        $offers = Offer::all();

        // إنشاء 300 طلب
        for ($i = 0; $i < 300; $i++) {
            // تحديد نوع الطلب مع الأوزان
            $orderTypes = [
                ['type' => 'Delivery', 'weight' => 30],
                ['type' => 'Reservation', 'weight' => 40],
                ['type' => 'Takeaway', 'weight' => 30],
            ];

            $totalWeight = array_sum(array_column($orderTypes, 'weight'));
            $randomWeight = $faker->numberBetween(1, $totalWeight);
            $currentWeight = 0;
            $orderType = null;

            foreach ($orderTypes as $type) {
                $currentWeight += $type['weight'];
                if ($randomWeight <= $currentWeight) {
                    $orderType = $type['type'];
                    break;
                }
            }

            // تهيئة المتغيرات
            $customerId = null;
            $employeeId = null;
            $reservationId = null;
            $locationId = null;
            $status = 'Pending';
            $datedAt = $faker->dateTimeBetween('-1 year', '+1 month');

            // معالجة كل نوع طلب حسب شروطه
            switch ($orderType) {
                case 'Delivery':
                    // التوصيل: مرتبط بموقع وزبون
                    $customerId = $customers->random()->id;
                    $locationId = $locations->random()->id;

                    // تحديد حالة الطلب (40% مؤكد، 50% منتهي، 10% ملغي)
                    $statusRand = $faker->numberBetween(1, 100);
                    if ($statusRand <= 40) {
                        $status = 'Confirmed';
                    } elseif ($statusRand <= 90) {
                        $status = 'Completed';
                    } else {
                        $status = 'Cancelled';
                    }
                    break;

                case 'Reservation':
                    // الحجز: مرتبط بحجز وموظف
                    $reservation = $reservations->random();
                    $reservationId = $reservation->id;
                    $employeeId = $employees->random()->id;

                    // حالة الطلب تعتمد على حالة الحجز
                    switch ($reservation->status) {
                        case 'cancelled':
                            $status = 'Cancelled';
                            break;
                        case 'completed':
                            $status = 'Completed';
                            break;
                        default:
                            $status = 'Confirmed'; // الافتراضي مؤكد
                    }
                    break;

                case 'Takeaway':
                    // الطلب الخارجي: مرتبط بموظف فقط
                    $employeeId = $employees->random()->id;

                    // تحديد حالة الطلب (50% مؤكد، 40% منتهي، 10% ملغي)
                    $statusRand = $faker->numberBetween(1, 100);
                    if ($statusRand <= 50) {
                        $status = 'Confirmed';
                    } elseif ($statusRand <= 90) {
                        $status = 'Completed';
                    } else {
                        $status = 'Cancelled';
                    }
                    break;
            }

            // إنشاء الطلب
            $orderData = [
                'notes' => $faker->boolean(30) ? $faker->sentence : null,
                'order_type' => $orderType,
                'status' => $status,
                'dated_at' => $datedAt,
                'customer_id' => $customerId,
                'employee_id' => $employeeId,
                'reservation_id' => $reservationId,
                'location_id' => $locationId,
                'created_at' => $datedAt,
                'updated_at' => $datedAt,
            ];

            // إذا كانت الحالة ملغية أو مكتملة، نضيف تاريخ الحذف
            if (in_array($status, ['Cancelled', 'Completed'])) {
                $orderData['deleted_at'] = $datedAt;
            }

            $order = Order::create($orderData);

            // إضافة الوجبات للطلب
            $mealCount = $faker->numberBetween(1, 5);
            $selectedMeals = $meals->random($mealCount);

            foreach ($selectedMeals as $meal) {
                $order->meals()->attach($meal->id, [
                    'quantity' => $faker->numberBetween(1, 3),
                    'created_at' => $datedAt,
                    'updated_at' => $datedAt,
                ]);
            }

            // إضافة العروض للطلب (40% من الطلبات تحتوي على عرض)
            if ($faker->boolean(40)) {
                $offerCount = $faker->numberBetween(1, 2);
                $selectedOffers = $offers->random($offerCount);

                foreach ($selectedOffers as $offer) {
                    $order->offers()->attach($offer->id, [
                        'quantity' => 1,
                        'created_at' => $datedAt,
                        'updated_at' => $datedAt,
                    ]);
                }
            }

            // إذا كانت الحالة منتهية أو ملغية، التأكد من أن التاريخ قديم
            if (in_array($status, ['Completed', 'Cancelled']) && $datedAt > $now) {
                $order->dated_at = $faker->dateTimeBetween('-1 year', '-1 day');
                $order->deleted_at = $order->dated_at;
                $order->save();
            }
        }
    }
}
