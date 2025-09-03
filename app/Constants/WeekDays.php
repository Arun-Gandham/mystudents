<?php

namespace App\Constants;

class WeekDays
{
    public const LIST = [
        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
        'sat' => 'Saturday',
        'sun' => 'Sunday',
    ];

    /**
     * Get label from key (e.g., "mon" -> "Monday")
     */
    public static function label(string $key): string
    {
        return self::LIST[$key] ?? $key;
    }

    /**
     * Get key from label (e.g., "Monday" -> "mon")
     */
    public static function key(string $label): ?string
    {
        return array_search(ucfirst(strtolower($label)), self::LIST, true) ?: null;
    }
}
