<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'job_id', 'cover_letter'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobListings()
    {
        return $this->belongsToMany(JobListing::class, 'applicant_joblisting');
    }
}
