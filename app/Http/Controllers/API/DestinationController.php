<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Destination\DestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validatedData = $request->validated();
    
    // Handle the image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('destinations', 'public');
        $validatedData['image'] = $imagePath;
    }
    
    $destination = Destination::create($validatedData);
    
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
        $validatedData = $request->validated();
    
        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($destination->image) {
                Storage::disk('public')->delete($destination->image);
            }
    
            // Store the new image
            $imagePath = $request->file('image')->store('destinations', 'public');
            $validatedData['image'] = $imagePath;
        }
    
        $destination->update($validatedData);
    
        if ($request->has('visa_types')) {
            $destination->visaTypes()->sync($request->visa_types);
        }
    
        // Refresh the model to get the updated data
        $destination->refresh();
    
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