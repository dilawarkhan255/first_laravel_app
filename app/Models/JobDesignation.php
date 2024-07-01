<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDesignation extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function jobListing()
    {
        return $this->hasOne(JobListing::class, 'designation_id');
    }
}