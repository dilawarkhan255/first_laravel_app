<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDesignation extends Model
{
    use HasFactory;

    // Specify fillable attributes if necessary
    protected $fillable = ['name'];

    public function JobListing(){
        return $this->hasMany(JobListing::class, 'designation_id');
    }
}
