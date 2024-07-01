<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title', 'company', 'designation_id', 'location', 'description', 'status'
    ];

    public function designation()
    {
        return $this->belongsTo(JobDesignation::class, 'designation_id');
    }
}