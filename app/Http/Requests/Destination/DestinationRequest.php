<?php

namespace App\Http\Requests\Destination;

use Illuminate\Foundation\Http\FormRequest;

class DestinationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:destinations,code,' . ($this->destination ? $this->destination->id : 'NULL') . ',id',
            'visa_type_ids' => 'sometimes|array',
            'visa_type_ids.*' => 'exists:visa_types,id',
        ];
    }
}
