<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Comment;
use Tests\Fixtures\CustomNanoIdPost;
use Tests\Fixtures\MultipleNanoIdPost;
use Tests\Fixtures\Post;
use Tests\TestCase;

class NanoIdTest extends TestCase
{
    #[Test]
    public function it_sets_the_nano_id_when_creating_a_new_model(): void
    {
        $post = Post::create(['title' => 'Test post']);

        $this->assertNotNull($post->nanoId);
        $this->assertSame(21, strlen($post->nanoId));
    }

    #[Test]
    public function it_does_not_override_the_nano_id_if_it_is_already_set(): void
    {
        $nanoId = 'existingNanoIdValue123';

        $post = Post::create(['title' => 'Test post', 'nanoId' => $nanoId]);

        $this->assertSame($nanoId, $post->nanoId);
    }

    #[Test]
    public function it_finds_a_model_by_its_nano_id(): void
    {
        $nanoId = 'findableNanoIdValue12';

        Post::create(['title' => 'Test post', 'nanoId' => $nanoId]);

        $post = Post::whereNanoId($nanoId)->first();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame($nanoId, $post->nanoId);
    }

    #[Test]
    public function it_supports_custom_nano_id_column_names(): void
    {
        $post = CustomNanoIdPost::create(['title' => 'Test post']);

        $this->assertNull($post->nanoId);
        $this->assertNotNull($post->custom_nanoId);
        $this->assertSame(21, strlen($post->custom_nanoId));
    }

    #[Test]
    public function it_finds_a_model_by_a_custom_nano_id_column(): void
    {
        $nanoId = 'customNanoIdValue123';

        CustomNanoIdPost::create(['title' => 'Test post', 'custom_nanoId' => $nanoId]);

        $post = CustomNanoIdPost::whereNanoId($nanoId)->first();

        $this->assertInstanceOf(CustomNanoIdPost::class, $post);
        $this->assertSame($nanoId, $post->custom_nanoId);
    }

    #[Test]
    public function it_supports_multiple_nano_id_columns(): void
    {
        $post = MultipleNanoIdPost::create(['title' => 'Test post']);

        $this->assertNotNull($post->nanoId);
        $this->assertNotNull($post->custom_nanoId);
        $this->assertSame(21, strlen($post->nanoId));
        $this->assertSame(21, strlen($post->custom_nanoId));
    }

    #[Test]
    public function it_finds_a_model_by_a_declared_nano_id_column(): void
    {
        $nanoId = 'defaultNanoIdValue12';
        $customNanoId = 'customNanoIdValue123';

        MultipleNanoIdPost::create([
            'title' => 'Test post',
            'nanoId' => $nanoId,
            'custom_nanoId' => $customNanoId,
        ]);

        $defaultMatch = MultipleNanoIdPost::whereNanoId($nanoId)->first();
        $customMatch = MultipleNanoIdPost::whereNanoId($customNanoId, 'custom_nanoId')->first();

        $this->assertInstanceOf(MultipleNanoIdPost::class, $defaultMatch);
        $this->assertInstanceOf(MultipleNanoIdPost::class, $customMatch);
        $this->assertSame($nanoId, $defaultMatch->nanoId);
        $this->assertSame($customNanoId, $customMatch->custom_nanoId);
    }

    #[Test]
    public function it_finds_models_by_an_array_of_nano_ids(): void
    {
        Post::create(['title' => 'First post', 'nanoId' => 'firstNanoIdValue123']);
        Post::create(['title' => 'Second post', 'nanoId' => 'secondNanoIdValue12']);

        $count = Post::whereNanoId([
            'firstNanoIdValue123',
            'secondNanoIdValue12',
        ])->count();

        $this->assertSame(2, $count);
    }

    #[Test]
    public function it_allows_a_null_nano_id_column_when_model_events_are_disabled(): void
    {
        $post = EloquentModel::withoutEvents(function () {
            return Post::create([
                'title' => 'Nullable NanoId',
                'nanoId' => null,
            ]);
        });

        $this->assertNull($post->nanoId);
    }

    #[Test]
    public function it_handles_queries_with_related_models_that_use_nano_ids(): void
    {
        $post = Post::create([
            'title' => 'Test post',
            'nanoId' => 'postNanoIdValue1234',
        ]);

        $comment = $post->comments()->create([
            'body' => 'Test comment',
            'nanoId' => 'commentNanoIdValue1',
        ]);

        $matchedComment = $post->comments()->whereNanoId($comment->nanoId)->first();

        $this->assertNotNull($matchedComment);
        $this->assertSame('postNanoIdValue1234', $matchedComment->post->nanoId);
    }

    #[Test]
    public function it_throws_when_a_nano_id_does_not_exist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        Post::whereNanoId('missingNanoIdValue12')->firstOrFail();
    }
}
