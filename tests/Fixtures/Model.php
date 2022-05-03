<?php

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Parables\NanoId\GeneratesNanoId;

abstract class Model extends BaseModel
{
    use GeneratesNanoId;

    /**
     * {@inheritdoc}
     */
    protected $table = 'posts';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
