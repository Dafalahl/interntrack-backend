<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApplicationService;
use App\Models\Application;
use Illuminate\Http\Request;
use Exception;

class ApplicationController extends Controller
{
    protected ApplicationService $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function store(Request $request, $jobId)
    {
        try {
            $application = $this->applicationService->applyForJob($request->user(), (int) $jobId);
            return response()->json([
                'message' => 'Lamaran magang berhasil diajukan.',
                'data' => $application
            ], 201);
        } catch (Exception $e) {
            $statusCode = in_array($e->getCode(), [400, 409, 422]) ? $e->getCode() : 500;
            return response()->json(['message' => $e->getMessage()], $statusCode);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:accepted,rejected']);
        $application = Application::findOrFail($id);

        if ($request->user()->cannot('updateStatus', $application)) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $updated = $this->applicationService->updateApplicationStatus($application, $request->status);
        return response()->json([
            'message' => 'Status lamaran berhasil diperbarui.',
            'data' => $updated
        ], 200);
    }

    public function myApplications(Request $request)
    {
        $studentProfile = $request->user()->studentProfile;
        if (!$studentProfile) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan.'], 404);
        }

        $applications = Application::with('job')
            ->where('student_id', $studentProfile->id)
            ->latest()
            ->get();

        return response()->json(['data' => $applications], 200);
    }
}