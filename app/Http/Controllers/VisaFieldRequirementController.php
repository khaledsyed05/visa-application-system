<?php

namespace App\Http\Controllers;

use App\Models\VisaFieldRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function storeCost(Request $request)
    {
        $validated = $request->validate([
            'visa_type_id' => 'required|exists:visa_types,id',
            'destination_id' => 'required|exists:destinations,id',
            'cost' => 'required|numeric|min:0',
        ]);

        $result = DB::table('visa_types_destinations')->updateOrInsert(
            [
                'visa_type_id' => $validated['visa_type_id'],
                'destination_id' => $validated['destination_id'],
            ],
            [
                'cost' => $validated['cost'],
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        if ($result) {
            $updatedRecord = DB::table('visa_types_destinations')
                ->where('visa_type_id', $validated['visa_type_id'])
                ->where('destination_id', $validated['destination_id'])
                ->first();

            return response()->json([
                'message' => 'Cost updated successfully',
                'data' => $updatedRecord
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update cost'
        ], 500);
    }

    public function getCost(Request $request)
    {
        $validated = $request->validate([
            'visa_type_id' => 'required|exists:visa_types,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $cost = DB::table('visa_types_destinations')
            ->where('visa_type_id', $validated['visa_type_id'])
            ->where('destination_id', $validated['destination_id'])
            ->value('cost');

        return response()->json([
            'cost' => $cost
        ]);
    }
}
