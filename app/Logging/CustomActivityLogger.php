<?php

namespace App\Logging;

use Spatie\Activitylog\ActivityLogger;
use Illuminate\Support\Facades\Request;

class CustomActivityLogger extends ActivityLogger
{
    public function customLog(string $description)
    {

        $this->withProperty('ip_address', Request::ip());

        return $this->log($description);
    }
}
