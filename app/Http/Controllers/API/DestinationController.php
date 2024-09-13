<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Destination\DestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destinations = Destination::with('visaTypes')->get();
        return response()->json($destinations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DestinationRequest $request)
    {
        $destination = Destination::create($request->validated());
        
        if ($request->has('visa_types')) {
            $destination->visaTypes()->sync($request->visa_types);
        }
        
        return response()->json($destination->load('visaTypes'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return response()->json($destination->load('visaTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DestinationRequest $request, Destination $destination)
    {
        $destination->update($request->validated());
        
        if ($request->has('visa_types')) {
            $destination->visaTypes()->sync($request->visa_types);
        }
        
        return response()->json($destination->load('visaTypes'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        $destination->visaTypes()->detach();
        $destination->delete();
        return response()->json(null, 204);
    }

}