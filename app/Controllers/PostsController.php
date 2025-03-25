<?php
namespace App\Controllers;
use Bdt\Example\Controller;
use Bdt\Example\Response;
use Bdt\Example\Request;
use Bdt\Example\View;
use App\Models\Post;
error_reporting(E_ALL);
ini_set('display_errors', 1);

class PostsController extends Controller
{
    public function index(Request $request): Response
    {
      
        $posts = Post::findAll();
        $view = new View('index.html');
        $view->set('title', 'Posts');
        $view->set('posts', $posts);
        return new Response($view->render());  
    
    }
 
    public function add(Request $request): Response
    {
        $data = $this->getPostData();
        if ($this->hasMissingFields($data)) {
            return $this->respondWithError('Missing required fields', 302);
        }
        $post = new Post();
        $this->savePost($post, $data);
        return $this->respondWithSuccess('Post added successfully!', $post, 201);
    }

    public function update(Request $request): Response
    {
        $data = $this->getPostData(['id', 'title', 'body', 'author']);
        $data['id'] = (int) $data['id'];

        if ($this->hasMissingFields($data)) {
            return $this->respondWithError('Missing required fields', 400);
        }
        $post = Post::findById($data['id']);
        if ($post) {

            $this->savePost($post, $data);
            return $this->respondWithSuccess('Post updated successfully!', $post, 200);
        } else {
            return new Response('Post not found', ['Location' => '/'], 404);
        }

    }
    public function delete(Request $request): Response
    {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            return new Response('Missing required field', [], 400); 
        }
      
        $post = Post::findById( $id);
        if ($post) {
            $post->delete();
            return new Response('', ['Location: /'], 302);
        } else {
            return new Response('Post not found', [], 404);
        } 
    } 
   
    /**
     * @param array<string> $requiredFields
     * @return array<string, string|null|int>
     */
    private function getPostData(array $requiredFields = ['title', 'body', 'author']): array
    {
        $data = [];
        foreach ($requiredFields as $field) {
            $data[$field] = $_POST[$field] ?? null;
        }
        return $data;
    }
     /**
     * @param array<string, string|null|int> $data
     * @return bool
     */
    private function hasMissingFields(array $data): bool
    {
        return empty($data['title']) || empty($data['body']) || empty($data['author']);
    }
    /**
     * @param array<string, string|null|int> $data
     * @return void
     */
    private function savePost(Post $post, array $data): void
    {
        $post->set('title', $data['title']);
        $post->set('body', $data['body']);
        $post->set('author', $data['author']);
        $post->save();
    }
    /**
     * @param string $message
     * @param Post $post
     * @param int $status
     * @return Response
     */
    private function respondWithSuccess(string $message, Post $post, int $status): Response
    {
        $responseData = json_encode([
            'message' => $message,
            'post' => $post
        ]);

        if ($responseData === false) {
            $responseData = '{"error": "Failed to encode response"}';
        }
        return new Response($responseData, ['Content-Type: application/json'], $status);
    }
    /**
     * @param string $message
     * @param int $status
     * @return Response
    */
    private function respondWithError(string $message, int $status): Response
    {
        return new Response($message, ['Location: /'], $status);
    }
}