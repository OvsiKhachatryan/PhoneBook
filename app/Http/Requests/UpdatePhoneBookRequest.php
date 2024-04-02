<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoneBookRequest extends FormRequest
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
            'country_id' => 'sometimes|required|exists:countries,id',
            'timezone_id' => 'sometimes|required|exists:timezones,id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'phone_number' => 'sometimes|required|string|regex:/^\+\d{2}\s\d{3}\s\d{9}$/',
            'insertedOn' => 'sometimes|required|date',
            'updatedOn' => 'sometimes|required|date',
        ];
    }
}
