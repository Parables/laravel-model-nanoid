<?php

namespace Tests\Fixtures;

use Parables\NanoId\BindsOnNanoId;
use Parables\NanoId\GeneratesNanoId;

class NanoIdRouteBoundPost extends Model
{
    use BindsOnNanoId;
    use GeneratesNanoId;

    public static function nanoIdColumn(): string
    {
        return 'nanoId';
    }
}
