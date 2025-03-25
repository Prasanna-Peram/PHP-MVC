<?php

namespace Bdt\Example;

class Response
{
    public string $content;
    /** @var array<string> */
    public array $headers;
    public int $status;

    /** @param array<string> $headers */
    public function __construct(string $content, array $headers = [], int $status = 200)
    {
        $this->content = $content;
        $this->headers = $headers;
        $this->status = $status;
    }

    public function render(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            if (is_numeric($key)) {
                $content = $value;
            } else {
                $content = "{$key}: {$value}";
            }
            header($content, true);
        }

        echo $this->content;
    }
}