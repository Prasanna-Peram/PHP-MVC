<?php

namespace Tests\Unit;

use Bdt\Example\Request;
use Bdt\Example\Route;
use Bdt\Example\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function test_it_sends_404(): void
    {
        $app = new Application();
        $response = $app->run(new Request([
            'REQUEST_URI' => '/not-found',
            'REQUEST_METHOD' => 'GET',
        ]));
        $this->assertEquals(404, $response->status);
    }

    public function test_it_prevents_bad_paths(): void
    {
        $app = new Application();
        $response = $app->run(new Request([
            'REQUEST_URI' => '/../composer.json',
            'REQUEST_METHOD' => 'GET',
        ]));
        $this->assertEquals(400, $response->status);
    }

    public function test_it_loads_static_files(): void
    {
        $app = new Application();
        $response = $app->run(new Request([
            'REQUEST_URI' => '/favicon.ico',
            'REQUEST_METHOD' => 'GET',
        ]));
        $this->assertEquals(200, $response->status);
    }

    public function test_it_wont_load_php(): void
    {
        $app = new Application();
        $response = $app->run(new Request([
            'REQUEST_URI' => '/index.php',
            'REQUEST_METHOD' => 'GET',
        ]));
        $this->assertEquals(400,$response->status);
    }

    public function test_it_checks_methods(): void
    {
        $app = (new Application())->routes([
            new Route('/', 'POST', 'App\Controllers\PostsController', 'index')
        ]);

        $response = $app->run(new Request([
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
        ]));

        $this->assertEquals(405, $response->status);
    }
}