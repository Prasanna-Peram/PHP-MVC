<?php

namespace Bdt\Example;

class Route
{
    /**
     * @param class-string $class
     * @param array<string>|string $methods
     */
    public function __construct(
        public string $path,
        public array|string $methods,
        public string $class,
        public string $action,
    )
    {
    }
}