<?php

namespace App\Http\Requests\Api\MaintenancesRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class PostMaintenancesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => 'required|numeric|digits_between:1,20',
            'discount' => 'sometimes|numeric|digits_between:1,11',
            'notes' => 'sometimes',
            'maintenance_item' => 'required|max:16'
        ];
    }
    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
                'status' =>422
            ], 422)
        );

    }
}
