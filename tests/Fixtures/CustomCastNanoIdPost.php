<?php

namespace Tests\Fixtures;

class CustomCastNanoIdPost extends Model
{
    public static function nanoIdColumn(): string
    {
        return 'custom_nanoId';
    }
}
