<?php

namespace Tests\Fixtures;

use Parables\NanoId\BindsOnNanoId;
use Parables\NanoId\GeneratesNanoId;

class CustomNanoIdRouteBoundPost extends Model
{
    use BindsOnNanoId;
    use GeneratesNanoId;

    public static function nanoIdColumn(): string
    {
        return 'custom_nanoId';
    }
}
