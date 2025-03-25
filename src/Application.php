<?php

namespace Bdt\Example;

use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;

class Application
{
    /** @var array<string, mixed> */
    public array $server;
    /** @var array<string, mixed>*/
    public array $post;
    /** @var array<string, mixed> */
    public array $get;
    /** @var array<string, Route> */
    private array $routes = [];
    private Connection $connection;

    /** @param array<Route> $routes */
    public function routes(array $routes): self
    {
        foreach ($routes as $route) {
            $this->route($route->path, $route);
        }

        return $this;
    }

    public function route(string $path, Route $route): void
    {
        $this->routes[$path] = $route;
    }

    public function run(Request $request): Response
    {
        $urlParts = parse_url($request->server['REQUEST_URI']);
        assert(isset($urlParts['path']));
        $currentPath = $urlParts['path'];

        // Serve static files if they exist
        $realPath = realpath(__DIR__ ."/../public/{$currentPath}");
        if ($realPath !== false && is_file($realPath)) {
            if (pathinfo($realPath, PATHINFO_EXTENSION) === 'php') {
                return new Response('Bad Request', [], 400);
            }

            if (strpos($realPath, (string) realpath(__DIR__ ."/../public/")) !== 0) {
                return new Response('Bad Request', [], 400);
            }

            return new Response((string) file_get_contents($realPath), [], 200);
        }

        foreach ($this->routes as $path => $route) {
            // Check route
            if ($currentPath === $path) {
                // Check method
                $methods = (array) $route->methods;
                if (!in_array($request->server['REQUEST_METHOD'], $methods)) {
                    return new Response('Method Not Allowed', [], 405);
                }

                $class = new $route->class($this);
                return $class->{$route->action}($request);
            }
        }

        return new Response('Not Found', [], 404);
    }

    /** @param array<string, mixed> $connectionParams */
    public function database(array $connectionParams): self
    {
        $this->connection = DriverManager::getConnection($connectionParams, new Configuration());
        BaseModel::setDefaultConnection($this->connection);
        return $this;
    }

    public function migrate(Schema $schema): self
    {
        $schemaManager = $this->connection->createSchemaManager();
        $schemaManager->migrateSchema($schema);
        return $this;
    }   
}