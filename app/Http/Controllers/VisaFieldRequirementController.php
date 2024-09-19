<?php

namespace App\Http\Controllers;

use App\Models\VisaFieldRequirement;
use Illuminate\Http\Request;

class VisaFieldRequirementController extends Controller
{

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'field_name' => 'required|string',
            'visa_type_id' => 'required|exists:visa_types,id',
            'destination_id' => 'required|exists:destinations,id',
            'is_required' => 'required|boolean',
        ]);

        $requirement = VisaFieldRequirement::updateOrCreate(
            [
                'field_name' => $validated['field_name'],
                'visa_type_id' => $validated['visa_type_id'],
                'destination_id' => $validated['destination_id'],
            ],
            ['is_required' => $validated['is_required']]
        );

        return response()->json([
            'message' => 'Field requirement updated',
            'requirement' => $requirement
        ]);
    }
    public function getRequirements(Request $request)
    {
        $validated = $request->validate([
            'visa_type_id' => 'required|exists:visa_types,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $requirements = VisaFieldRequirement::where('visa_type_id', $validated['visa_type_id'])
            ->where('destination_id', $validated['destination_id'])
            ->where('is_required', true)
            ->pluck('field_name')
            ->toArray();

        return response()->json([
            'required_fields' => $requirements
        ]);
    }
}
