<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FilterSettings extends Settings
{
    public ?int $user_id;
    
    public static function group(): string
    {
        return 'general';
    }
}