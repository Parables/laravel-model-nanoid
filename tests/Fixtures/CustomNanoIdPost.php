<?php

namespace Tests\Fixtures;

class CustomNanoIdPost extends Model
{
    public static function nanoIdColumn(): string
    {
        return 'custom_nanoId';
    }
}
