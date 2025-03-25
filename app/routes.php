<?php

use Bdt\Example\Route;
use App\Controllers\PostsController;


return [
    new Route('/', 'GET', PostsController::class, 'index'),
    new Route('/api/add', 'POST', PostsController::class, 'add'),
    new Route('/api/update', 'POST', PostsController::class, 'update'),
    new Route('/api/delete', 'POST', PostsController::class, 'delete')
];
