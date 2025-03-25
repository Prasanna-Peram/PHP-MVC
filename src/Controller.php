<?php

namespace Bdt\Example;

use Bdt\Example\Application;

class Controller
{
    public function __construct(protected Application $application)
    {
    }
}