<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function designation()
    {
        return $this->belongsTo(JobDesignation::class, 'designation_id');
    }

    public function applicants()
    {
        return $this->belongsToMany(Applicant::class, 'applicant_joblisting');
    }

    public function favouritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favourite_joblisting')
                    ->withPivot('favourite')
                    ->withTimestamps();
    }

}
