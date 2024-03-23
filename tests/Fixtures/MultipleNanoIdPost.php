<?php

namespace Tests\Fixtures;

class MultipleNanoIdPost extends Model
{
    public function nanoIdColumns(): array
    {
        return ['nanoId', 'custom_nanoId'];
    }
}
