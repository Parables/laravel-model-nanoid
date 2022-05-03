<?php

namespace Tests\Fixtures;

class CustomCastNanoIdPost extends Model
{
    public function nanoIdColumn(): string
    {
        return 'custom_nanoid';
    }
}
