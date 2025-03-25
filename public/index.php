<?php

use Bdt\Example\Application;
use Bdt\Example\Model;
use Bdt\Example\Request;

error_reporting(-1);
require_once __DIR__.'/../vendor/autoload.php';

$connectionParams = [
    'user' => '',
    'password' => '',
    'path' => __DIR__.'/../db.sqlite',
    'driver' => 'pdo_sqlite',
];

// Route application to different controller actions.
(new Application)
    ->database($connectionParams)
    ->migrate(require_once __DIR__.'/../app/schema.php')
    ->routes(require_once __DIR__.'/../app/routes.php')
    ->run(new Request($_SERVER, $_POST, $_GET))
    ->render();
