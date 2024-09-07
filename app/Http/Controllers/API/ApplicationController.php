<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Mail\ApplicationStatusChangedMail;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\ApplicationSubmitted;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the applications.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Application::with(['visaType', 'destination']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(20);

        return response()->json($applications);
    }

    /**
     * Store a newly created application in storage.
     */
    public function store(ApplicationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $passportPath = $request->file('passport_file')->store('passports', 'public');
        $photoPath = $request->file('photo_file')->store('photos', 'public');

        $application = Application::create([
            'visa_type_id' => $validated['visa_type_id'],
            'destination_id' => $validated['destination_id'],
            'applicant_name' => $validated['applicant_name'],
            'email' => $validated['email'],
            'passport_file' => $passportPath,
            'photo_file' => $photoPath,
            'additional_info' => $validated['additional_info'],
            'phone_number'=> $validated['phone_number'],
        ]);

        try {
            Mail::send(new ApplicationSubmitted($application));
        } catch (\Exception $e) {
            // Log the error, but don't expose it to the user
            \Log::error('Failed to send email for application ID: ' . $application->id . '. Error: ' . $e->getMessage());
        }
    
        return response()->json(['message' => 'Application submitted successfully', 'id' => $application->id], 201); }

    /**
     * Display the specified application.
     */
    public function show(Application $application): JsonResponse
    {
        $application->load(['visaType', 'destination']);
        return response()->json($application);
    }

    /**
     * Update the specified application in storage.
     */
    public function update(Request $request, Application $application): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $application->status;
        $application->update($validated);

        if ($oldStatus !== $application->status) {
            try {
                Mail::to($application->email)->send(new ApplicationStatusChangedMail($application));
                Log::info('Status change email sent for application ID: ' . $application->id);
            } catch (\Exception $e) {
                Log::error('Failed to send status change email for application ID: ' . $application->id . '. Error: ' . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Application updated successfully']);
    }

    /**
     * Remove the specified application from storage.
     */
    public function destroy(Application $application): JsonResponse
    {
        Storage::disk('public')->delete([$application->passport_file, $application->photo_file]);

        $application->delete();

        return response()->json(['message' => 'Application deleted successfully']);
    }

}