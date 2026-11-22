<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class ApplicationService
{
    /**
     * Logika Mahasiswa Mengajukan Lamaran Magang
     */
    public function applyForJob(User $studentUser, int $jobId): Application
    {
        $profile = $studentUser->studentProfile;
        if (!$profile) {
            throw new Exception("Profil mahasiswa belum dilengkapi.", 422);
        }

        $job = Job::findOrFail($jobId);
        if ($job->status !== 'open') {
            throw new Exception("Lowongan magang ini sudah ditutup.", 400);
        }

        // Pengecekan duplikasi pengajuan lamaran (HTTP 409 Conflict)
        $alreadyApplied = Application::where('job_id', $jobId)
            ->where('student_id', $profile->id)
            ->exists();

        if ($alreadyApplied) {
            throw new Exception("Anda sudah mengajukan lamaran pada lowongan ini.", 409);
        }

        return DB::transaction(function () use ($job, $profile) {
            return Application::create([
                'job_id' => $job->id,
                'student_id' => $profile->id,
                'status' => 'pending',
                'applied_at' => now(),
            ]);
        });
    }

    /**
     * Logika Mitra Mengubah Status Seleksi Pelamar
     */
    public function updateApplicationStatus(Application $application, string $newStatus): Application
    {
        return DB::transaction(function () use ($application, $newStatus) {
            $application->update(['status' => $newStatus]);

            if ($newStatus === 'accepted') {
                $job = $application->job;
                if ($job && $job->quota > 0) {
                    $job->decrement('quota');
                }
            }

            return $application;
        });
    }
}
