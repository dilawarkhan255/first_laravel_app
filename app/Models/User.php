<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'provider_id',
        'provider',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    public function favouriteJobs()
    {
        return $this->belongsToMany(JobListing::class, 'favourite_joblisting')
                    ->withPivot('favourite')
                    ->wherePivot('favourite', false)
                    ->withTimestamps();
    }

    public function children()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function parent()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
}
