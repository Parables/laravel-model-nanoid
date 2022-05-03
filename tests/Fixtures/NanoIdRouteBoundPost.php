<?php

namespace Tests\Fixtures;

use Parables\NanoId\GeneratesNanoId;

class NanoIdRouteBoundPost extends Model
{
    use GeneratesNanoId;

    public function nanoIdColumn(): string
    {
        return 'nanoid';
    }
}
