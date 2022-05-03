<?php

namespace Tests\Fixtures;

class MultipleNanoIdPost extends Model
{
    public function nanoIdColumns(): array
    {
        return ['nanoid', 'custom_nanoid'];
    }
}
