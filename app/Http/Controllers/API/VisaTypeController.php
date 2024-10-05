<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisaTypeRequest;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visa = VisaType::all();
        return response()->json($visa);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisaTypeRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('visa-type-icons', 'public');
            $validated['icon'] = $path;
        }

        $visaType = VisaType::create($validated);

        return response()->json($visaType, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(VisaType $visaType)
    {
        return response()->json($visaType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VisaTypeRequest $request, VisaType $visaType)
    {
        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($visaType->icon) {
                Storage::disk('public')->delete($visaType->icon);
            }

            $path = $request->file('icon')->store('visa-type-icons', 'public');
            $validated['icon'] = $path;
        }

        $visaType->update($validated);

        return response()->json($visaType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VisaType $visaType)
    {
        if ($visaType->icon) {
            Storage::disk('public')->delete($visaType->icon);
        }

        $visaType->delete();

        return response()->json(null, 204);
    }
}
