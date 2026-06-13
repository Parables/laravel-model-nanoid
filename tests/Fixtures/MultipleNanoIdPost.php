<?php

namespace Tests\Fixtures;

class MultipleNanoIdPost extends Model
{
    public static function nanoIdColumns(): array
    {
        return ['nanoId', 'custom_nanoId'];
    }
}
