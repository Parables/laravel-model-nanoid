<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase($this->app);
    }

    protected function setupDatabase($app)
    {
        Schema::dropAllTables();

        $app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nanoId')->nullable();
            $table->string('custom_nanoId')->nullable();
            $table->string('title');
        });

        $app['db']->connection()->getSchemaBuilder()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('post_id');
            $table->string('nanoId')->nullable();
            $table->text('body');
        });
    }
}
