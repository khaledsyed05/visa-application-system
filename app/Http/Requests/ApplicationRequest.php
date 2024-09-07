<?php

namespace App\Http\Requests;

use App\Models\RequirementChecklist;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
            'visa_type_id' => 'required|exists:visa_types,id',
            'destination_id' => 'required|exists:destinations,id',
            'applicant_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'passport_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4048',
            'photo_file' => 'required|image|mimes:jpg,jpeg,png|max:4048',
            'phone_number'=> 'required',
            'additional_info'=> 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'visa_type_id.required' => 'Please select a visa type.',
            'destination_id.required' => 'Please select a destination.',
            'applicant_name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'passport_file.required' => 'Please upload your passport document.',
            'passport_file.mimes' => 'The passport document must be a PDF, JPG, or PNG file.',
            'passport_file.max' => 'The passport document must not exceed 4MB.',
            'photo_file.required' => 'Please upload your photo.',
            'photo_file.image' => 'The photo must be an image file.',
            'photo_file.mimes' => 'The photo must be a JPG or PNG file.',
            'photo_file.max' => 'The photo must not exceed 4MB.',
            
        ];
    }
}
