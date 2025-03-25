<?php

namespace Bdt\Example;

class Request
{
    /**
     * @param array<string, mixed> $server
     * @param array<string, mixed> $post
     * @param array<string, mixed> $get
     */
    public function __construct(public array $server, public array $post = [], public array $get = [])
    {
    }
}