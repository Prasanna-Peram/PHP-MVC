<?php

namespace Bdt\Example;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{
    private Environment $twig;
    /** @var array<string, mixed> */
    private $data;
    private string $filename;


    public function __construct(string $filename)
    {
        $loader = new FilesystemLoader(__DIR__.'/../app/views');
        $this->twig = new Environment($loader, []);
        $this->filename = $filename;
    }

    public function set(string $k, mixed $v): void
    {
        $this->data[$k] = $v;
    }

    public function render(): string
    {
        return $this->twig->render($this->filename, $this->data);
    }
}