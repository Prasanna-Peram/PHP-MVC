<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Bdt\Example\Application;
use Bdt\Example\Response;
use Bdt\Example\Request;

class TestCase extends PHPUnitTestCase
{
    protected Application $application;

    public function setUp(): void
    {
        $this->application = (new Application)
            ->database(connectionParams: [
                'dbname' => 'example',
                'memory' => true,
                'driver' => 'pdo_sqlite',
            ])
            ->migrate(require __DIR__.'/../../app/schema.php')
            ->routes(require __DIR__.'/../../app/routes.php');  
    }

    /**
     * @param array<string, mixed> $post
     * @param array<string, mixed> $get
     */
    protected function call(string $method, string $path, array $post = [], array $get = []): Response
    {
        return $this->application->run(new Request([
            'REQUEST_URI' => $path,
            'REQUEST_METHOD' => $method,
        ], $post, $get));
    }
}