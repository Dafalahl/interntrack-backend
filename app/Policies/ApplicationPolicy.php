<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;

class ApplicationPolicy
{
    // Hanya mitra pemilik lowongan yang boleh terima/tolak pelamar
    public function updateStatus(User $user, Application $application): bool
    {
        return $user->role === 'penyedia' 
            && $user->id === $application->job->provider_id;
    }
}
