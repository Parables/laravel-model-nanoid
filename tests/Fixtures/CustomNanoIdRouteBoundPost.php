<?php

namespace Tests\Fixtures;

use Parables\NanoId\GeneratesNanoId;

class CustomNanoIdRouteBoundPost extends Model
{
    use GeneratesNanoId;

    public function nanoIdColumn(): string
    {
        return 'custom_nanoid';
    }
}
