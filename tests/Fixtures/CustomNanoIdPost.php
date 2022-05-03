<?php

namespace Tests\Fixtures;

class CustomNanoIdPost extends Model
{
    public function nanoIdColumn(): string
    {
        return 'custom_nanoid';
    }
}
