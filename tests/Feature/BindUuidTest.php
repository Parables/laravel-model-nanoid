<?php

namespace Tests\Feature;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Tests\Fixtures\CustomNanoIdRouteBoundPost;
use Tests\Fixtures\MultipleNanoIdRouteBoundPost;
use Tests\Fixtures\NanoIdRouteBoundPost;
use Tests\TestCase;

class BindUuidTest extends TestCase
{
    /** @test */
    public function it_binds_to_default_uuid_field()
    {
        $post = factory(NanoIdRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (NanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/' . $post->uuid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_default_uuid_field_value()
    {
        $post = factory(NanoIdRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (NanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/' . $post->custom_nanoId)->assertNotFound();
        $this->get(route('posts.show', $post->custom_nanoId))->assertNotFound();
    }

    /** @test */
    public function it_binds_to_custom_nanoId_field()
    {
        $post = factory(CustomNanoIdRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/' . $post->custom_nanoId)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_custom_nanoId_field_value()
    {
        $post = factory(CustomNanoIdRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/' . $post->uuid)->assertNotFound();
        $this->get(route('posts.show', $post->uuid))->assertNotFound();
    }

    /** @test */
    public function it_binds_to_declared_uuid_column_instead_of_default_when_custom_key_used()
    {
        $post = factory(MultipleNanoIdRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_nanoId}', function (MultipleNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/' . $post->custom_nanoId)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_uuid_when_custom_route_key_used()
    {
        $post = factory(MultipleNanoIdRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_nanoId}', function (MultipleNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/' . $post->uuid)->assertNotFound();
        $this->get(route('posts.show', $post->uuid))->assertNotFound();
    }
}
