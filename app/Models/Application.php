<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'job_id', 'student_id', 'status', 'applied_at',
    ];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}