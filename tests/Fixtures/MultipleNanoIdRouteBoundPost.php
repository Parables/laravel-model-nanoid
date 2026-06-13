<?php

namespace Tests\Fixtures;

use Parables\NanoId\BindsOnNanoId;
use Parables\NanoId\GeneratesNanoId;

class MultipleNanoIdRouteBoundPost extends Model
{
    use BindsOnNanoId;
    use GeneratesNanoId;

    public static function nanoIdColumns(): array
    {
        return [
            'nanoId', 'custom_nanoId',
        ];
    }
}
