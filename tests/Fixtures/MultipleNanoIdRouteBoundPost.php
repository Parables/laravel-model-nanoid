<?php

namespace Tests\Fixtures;

use Parables\NanoId\GeneratesNanoId;

class MultipleNanoIdRouteBoundPost extends Model
{
    use GeneratesNanoId;

    public function nanoIdColumns(): array
    {
        return [
            'nanoid', 'custom_nanoid',
        ];
    }
}
