<?php

namespace App\Http\Controllers\Api\ReservationsControllers;

use App\Mail\ReservationCodeMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReservationsRequests\postReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class postReservationController extends Controller
{
    public function add_reservation(postReservationRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'Unauthenticated.',
                    'data' => null,
                ], 401);
            }

            if ($user->user_type === 'Customer' && $user->customer?->isBanned()) {
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'account_banned',
                    'data' => null,
                ], 403);
            }
            $validatedData['reservation_code'] = Str::random(5);
            $validatedData['status'] = $request->status ?? 'Pending';
            $tableIds = $validatedData['table_ids'];
            unset($validatedData['table_ids']);

            if ($user->user_type === 'Employee' || $user->user_type === 'Manager') {
                $validatedData['type'] = 'Local';
                $validatedData['employee_id'] = $user->employee?->id;
            } elseif ($user->user_type === 'Customer') {
                $validatedData['type'] = 'Application';
                $validatedData['customer_id'] = $user->customer?->id;
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'Unauthorized user type.',
                    'data' => null,
                ], 403);
            }

            $reservation = Reservation::create($validatedData);

            if ($reservation && !empty($tableIds)) {
                $tables = Table::find($tableIds);
                $tableNumbers = $tables->pluck('table_number')->toArray();

                foreach ($tableIds as $tableId) {
                    $reservation->tables()->attach($tableId, [
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                    ]);
                }

                if ($user->user_type === 'Customer') {
                    Mail::to($user->email)->send(
                        new ReservationCodeMail($reservation, $tableNumbers)
                    );
                }
            }

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'The reservation has been saved successfully.',
                'data' => [
                    'reservation' => $reservation,
                    'table_numbers' => $tableNumbers ?? []
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Reservation creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Reservation creation failed',
                'data' => null,
            ], 500);
        }
    }
}
