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
use App\Services\VisaValidationService;

class ApplicationController extends Controller
{
    protected $validationService;

    public function __construct(VisaValidationService $validationService)
    {
        $this->validationService = $validationService;
    }
    /**
     * Display a listing of the applications.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Application::with(['visaType', 'destination']);

        $applications = $query->latest()->paginate(20);

        return response()->json($applications);
    }

    /**
     * Store a newly created application in storage.
     */
    public function store(Request $request)
    {
        // Get dynamic validation rules
        $rules = $this->validationService->getValidationRules($request->visa_type_id, $request->destination_id);
        
        $validated = $request->validate($rules);
 if($request->hasFile('passport_file')){
        $passportPath = $request->file('passport_file')->store('passports', 'public');
 }
 if($request->hasFile('photo_file')){
        $photoPath = $request->file('photo_file')->store('photos', 'public');
}
 if($request->hasFile('id_picture')){
        $idPath = $request->file('id_picture')->store('id pictures', 'public');
}
        $application = Application::create([
            'visa_type_id' => $validated['visa_type_id'] ?? null,
            'destination_id' => $validated['destination_id'] ?? null,
            'applicant_name' => $validated['applicant_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'passport_file' => $passportPath ?? null,
            'photo_file' => $photoPath ?? null,
            'id_picture' => $idPath ?? null,
            'additional_info' => $validated['additional_info'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
        ]);

        try {
            Mail::send(new ApplicationSubmitted($application));
        } catch (\Exception $e) {
            // Log the error, but don't expose it to the user
            Log::error('Failed to send email for application ID: ' . $application->id . '. Error: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Application submitted successfully', 'id' => $application->id], 201);
    }

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
    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'admin_notes' => 'nullable|array',
        ]);

        $oldStatus = $application->status;
        $application->update(['status' => $validated['status']]);
        $application = $application->fresh();

        $adminNotes = $validated['admin_notes'] ?? [];

        if ($oldStatus !== $application->status) {
            try {
                Mail::to($application->email)->send(new ApplicationStatusChangedMail($application, $adminNotes));
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
        if ($application->passport_file)
        {
        Storage::disk('public')->delete([$application->passport_file]);
        }
        if ( $application->photo_file)
        {
        Storage::disk('public')->delete([$application->photo_file]);
        }
        if ( $application->id_picture)
        {
        Storage::disk('public')->delete([$application->id_picture]);
        }
        $application->delete();

        return response()->json(['message' => 'Application deleted successfully']);
    }
}
