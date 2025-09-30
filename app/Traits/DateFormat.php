<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateFormat
{
    public function textFormatDate($date, $isComplete = false): object
    {
        $parsed = Carbon::parse($date);

        return (object) [
            'raw' => $parsed->format('Y-m-d'),
            'formatted' => $isComplete ? $parsed->format('d-m-Y H:i:s') : $parsed->format('d-m-Y'),
            'human' => $isComplete ? $parsed->translatedFormat('j \d\e F \d\e Y H:i') : $parsed->translatedFormat('j \d\e F \d\e Y'),
            'timestamp' => $parsed->timestamp,
            'iso' => $parsed->toISOString(),
            'diff' => $parsed->diffForHumans() // "hace 2 días"
        ];
    }
}