<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Models\JobPosting;

class JobController extends Controller
{
    // Daftar lowongan yang berstatus open
    public function index()
    {
        $jobs = JobPosting::where('status', 'open')->latest()->get();

        return response()->json([
            'data' => $jobs,
        ]);
    }

    // Rincian detail lowongan
    public function show($id)
    {
        $job = JobPosting::findOrFail($id);

        return response()->json([
            'data' => $job,
        ]);
    }

    // Mitra menyimpan lowongan baru
    public function store(StoreJobRequest $request)
    {
        $job = JobPosting::create([
            ...$request->validated(),
            'provider_id' => $request->user()->id,
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil dibuat',
            'data' => $job,
        ], 201);
    }
}