<?php

namespace App\Http\Requests\Api\ReservationsRequests;

use DB;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class postReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'party_size' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'table_ids' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $conflicts = DB::table('reservation_table')
                        ->whereIn('table_id', $value)
                        ->where(function ($query) {
                            $query->where(function ($q) {
                                $q->where('start_time', '<', $this->end_time)
                                    ->where('end_time', '>', $this->start_time);
                            });
                        })
                        ->exists();

                    if ($conflicts) {
                        $fail('Some tables are already reserved for the requested time slot.');
                    }
                }
            ],
            'table_ids.*' => 'exists:tables,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
                'status' => 422
            ], 422)
        );
    }
}
