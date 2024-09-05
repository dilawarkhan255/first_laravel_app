<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class CustomActivity extends SpatieActivity
{
    protected $guarded = [];
}
