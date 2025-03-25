<?php

namespace Tests\Feature;

use Bdt\Example\Application;
use Bdt\Example\BaseModel;
use Bdt\Example\Response;
use Bdt\Example\Request;
use App\Models\Post;
use Doctrine\DBAL\Connection;

class ModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Insert new Post for testing.
        $post = new Post();
        $post->set('title', 'Test Post');
        $post->set('body', 'Body of test post');
        $post->save();
    }

    public function test_getDefaultConnection(): void
    {
        $this->assertInstanceOf(Connection::class, BaseModel::getDefaultConnection());
    }

    public function test_findById(): void
    {
        $postId = Post::findAll()[0]->get('id');
        $post = Post::findById($postId);
        $this->assertNotFalse($post, "Post with ID $postId not found");
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($postId, $post->get('id'));
        $this->assertFalse(Post::findById(100));
    }

    public function test_findAll(): void
    {
        $posts = Post::findAll();
        $this->assertIsArray($posts);
        $this->assertInstanceOf(Post::class, $posts[0]);
    }

    public function test_insert(): void
    {
        $before = count(Post::findAll());
        $post = new Post();
        $post->set('title', 'Test Post');
        $post->set('body', 'Body of test post');
        $post->save();

        $after = count(Post::findAll());

        $this->assertEquals($before + 1, $after);
    }

    public function test_update(): void
    {
        $post = Post::findAll()[0];
        $this->assertNotNull($post, 'No post found for update.');
        $post->set('title', 'Updated Title');
        $post->save();
        $updatedPost = Post::findById($post->get('id'));
        $this->assertNotFalse($updatedPost, "Post with ID {$post->get('id')} not found after update.");
        $this->assertEquals('Updated Title', $updatedPost->get('title'), 'Post title was not updated correctly.');
    }

    public function test_delete(): void
    {
        $before = count(Post::findAll());
        $post = Post::findAll()[0];
        $post->delete();

        $after = count(Post::findAll());

        $this->assertEquals($before - 1, $after);
    }

    public function test_get_set_has():void
    {
        $post = new Post();
        $this->assertFalse($post->has('title'));

        $post->set('title', 'Test Post');
        $this->assertEquals('Test Post', $post->get('title'));
        $this->assertTrue($post->has('title'));
    }
}