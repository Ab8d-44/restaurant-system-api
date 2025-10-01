<?php

namespace App\Http\Controllers\Api\ReservationsControllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class DeleteReservationController extends Controller
{
    public function delete_reservation($reservation_id) 
    {
        $reservation = Reservation::find($reservation_id);

        if (!$reservation) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Reservation not found',
                'status' => 404
            ], 404);
        }

        try {
            $reservation->tables()->detach();
            $reservation->status = "Cancelled";
            
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => 'Reservation deleted successfully',
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            Log::error('Reservation deletion failed: ' . $e->getMessage());

            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Failed to delete reservation',
                'status' => 500
            ], 500);
        }
    }
}