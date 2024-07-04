<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public static function getAllStudents()
    {
        return self::all();
    }

    
}
