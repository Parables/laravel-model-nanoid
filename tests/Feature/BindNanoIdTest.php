<?php

namespace Tests\Feature;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\CustomNanoIdRouteBoundPost;
use Tests\Fixtures\MultipleNanoIdRouteBoundPost;
use Tests\Fixtures\NanoIdRouteBoundPost;
use Tests\TestCase;

class BindNanoIdTest extends TestCase
{
    #[Test]
    public function it_binds_to_the_default_nano_id_field(): void
    {
        $post = NanoIdRouteBoundPost::create(['title' => 'Test post']);

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (NanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->nanoId)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    #[Test]
    public function it_fails_on_invalid_default_nano_id_field_value(): void
    {
        $post = NanoIdRouteBoundPost::create([
            'title' => 'Test post',
            'custom_nanoId' => 'customNanoIdValue123',
        ]);

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (NanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_nanoId)->assertNotFound();
    }

    #[Test]
    public function it_binds_to_a_custom_nano_id_field(): void
    {
        $post = CustomNanoIdRouteBoundPost::create(['title' => 'Test post']);

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_nanoId)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    #[Test]
    public function it_fails_on_invalid_custom_nano_id_field_value(): void
    {
        $post = CustomNanoIdRouteBoundPost::create([
            'title' => 'Test post',
            'nanoId' => 'defaultNanoIdValue12',
        ]);

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->nanoId)->assertNotFound();
    }

    #[Test]
    public function it_binds_to_a_declared_nano_id_column_when_a_custom_route_key_is_used(): void
    {
        $post = MultipleNanoIdRouteBoundPost::create(['title' => 'Test post']);

        Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_nanoId}', function (MultipleNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_nanoId)->assertSuccessful();
    }

    #[Test]
    public function it_fails_on_invalid_nano_id_when_a_custom_route_key_is_used(): void
    {
        $post = MultipleNanoIdRouteBoundPost::create(['title' => 'Test post']);

        Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_nanoId}', function (MultipleNanoIdRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->nanoId)->assertNotFound();
    }
}
