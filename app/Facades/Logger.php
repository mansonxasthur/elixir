<?php

namespace App\Facades;

use Illuminate\Support\Facades\Log;

class Logger
{
    /**
     * @param \Exception $e
     */
    public static function debug(\Exception $e): void
    {
        Log::debug($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
    }
}