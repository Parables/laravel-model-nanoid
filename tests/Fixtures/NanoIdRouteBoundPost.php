<?php

namespace Tests\Fixtures;

use Parables\NanoId\GeneratesNanoId;

class NanoIdRouteBoundPost extends Model
{
    use GeneratesNanoId;

    public static function nanoIdColumn(): string
    {
        return 'nanoId';
    }
}
