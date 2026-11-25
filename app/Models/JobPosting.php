<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'provider_id', 'title', 'description', 'quota', 'status',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}