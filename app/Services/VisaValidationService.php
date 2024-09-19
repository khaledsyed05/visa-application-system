<?php

namespace App\Services;

use App\Models\VisaFieldRequirement;

class VisaValidationService
{
    public function getValidationRules($visaTypeId, $destinationId)
    {
        $baseRules = [
            // Add your base rules here
        ];

        $dynamicRules = VisaFieldRequirement::where('visa_type_id', $visaTypeId)
            ->where('destination_id', $destinationId)
            ->where('is_required', true)
            ->pluck('field_name')
            ->mapWithKeys(function ($field) {
                return [$field => 'required'];
            })
            ->toArray();

        return array_merge($baseRules, $dynamicRules);
    }
}