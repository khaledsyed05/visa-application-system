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
        $destination = Destination::all();
        return response()->json($destination);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(DestinationRequest $request)
    {
        $destination = Destination::create($request->validated());
        return response()->json($destination, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return response()->json($destination);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DestinationRequest $request, Destination $destination)
    {
        $destination->update($request->validated());
        return response()->json($destination);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();
        return response()->json(null, 204);
    }
}
