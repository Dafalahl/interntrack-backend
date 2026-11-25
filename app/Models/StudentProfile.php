<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'nim', 'prodi', 'gpa', 'cv_path', 'free_schedule',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}