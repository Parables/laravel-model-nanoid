<?php

namespace Tests;

use Dyrynda\Database\LaravelEfficientUuidServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(realpath(__DIR__ . '/database/factories'));

        $this->setupDatabase($this->app);
    }

    protected function setupDatabase($app)
    {
        Schema::dropAllTables();

        $app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->uuid('custom_nanoId')->nullable();
            $table->efficientUuid('efficient_uuid')->nullable();
            $table->string('title');
        });

        $app['db']->connection()->getSchemaBuilder()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('post_id');
            $table->uuid('uuid')->nullable();
            $table->text('body');
        });
    }
}
