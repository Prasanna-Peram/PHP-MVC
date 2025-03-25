<?php

namespace Tests\Feature;

use Bdt\Example\Application;
use Bdt\Example\Model;
use Bdt\Example\Response;
use Bdt\Example\Request;
use App\Models\Post;

class PostsControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        $connection = Post::getDefaultConnection();
        $connection->executeStatement('DELETE FROM posts'); // Clear any existing posts in the database before each test
        
        // Inserting new Post for testing - Prasanna
        $post = new Post();
        $post->set('title', 'Post 1');
        $post->set('body', 'Body of post 1');
        $post->set('author', 'Author 1');
        $post->save();
    }

    public function test_index_displays_form(): void
    {

        $response = $this->call('GET', '/');

        //checking that the existing post in the database is displayed in the list -Prasanna
        $post = Post::findById(1); 
        $this->assertNotFalse($post, 'Post with ID 1 should exist in the database');
        $this->assertStringContainsString($post->get('title'), $response->content);
        $this->assertStringContainsString($post->get('body'), $response->content);
        $this->assertStringContainsString($post->get('author'), $response->content);

        $this->assertStringContainsString('Create New Post', $response->content);
        $this->assertEquals(200, $response->status);
    }

    public function test_add_with_no_data_does_not_insert(): void
    {
        $response = $this->call('POST', '/api/add');

       // checking that there have been no posts added to the DB - Prasanna
        $postCountBefore = Post::count();
        $this->assertEquals(302, $response->status);
        $postCountAfter = Post::count();
        $this->assertEquals($postCountBefore, $postCountAfter, 'A new post should not have been added.');
        
        $this->assertEquals(['Location: /'], $response->headers);
    }

    public function test_add_with_data_inserts(): void
    {
       
        //checking that a new post has been added to the DB - Prasanna
        $postCountBefore = Post::count();
        $this->assertEquals(1, $postCountBefore, 'There should be 1 post in the database initially.');
        $response = $this->call('POST', '/api/add', [
            'author' => 'Authfoo',
            'title' => 'Foo',
            'body' => 'bar',
        ]);
    
        $this->assertEquals(302, $response->status);
        $this->assertEquals(['Location: /'], $response->headers);
        $postCountAfter = Post::count();
        echo  $postCountAfter;
        $this->assertEquals($postCountBefore, $postCountAfter, 'A new post should have been added.');
        $newPost = Post::findById($postCountAfter); // Should fetch the most recently added post
        $this->assertNotFalse($newPost, 'The new post should exist in the database.');
   
    }
}