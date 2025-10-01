<?php

namespace App\Http\Controllers\Api\ReservationsControllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Exception;

class GetOneReservationController extends Controller
{
    public function get_one_reservation($reservation_id) 
    {
        try {
            $reservation = Reservation::with('tables')->find($reservation_id);
            
            if (!$reservation) {
                return response()->json([
                    'data' => null,
                    'success' => false,
                    'message' => 'Reservation not found',
                    'status' => 404
                ], 404);
            }

            $reservation->tables->each->makeHidden('pivot');

            return response()->json([
                'data' => $reservation,
                'success' => true,
                'message' => null,
                'status' => 200
            ], 200);

        } catch (Exception $e) {
            Log::error('Failed to fetch reservation: ' . $e->getMessage());
            
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Failed to retrieve reservation',
                'status' => 500
            ], 500);
        }
    }
}