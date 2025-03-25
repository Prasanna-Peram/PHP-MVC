<?php

namespace Tests\Unit;

use Bdt\Example\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function test_it_renders(): void
    {
        $this->expectOutputString('foo');

        $request = new Response('foo', ['X-Foo' => 'Bar', 'NoKeyHeader'], 500);
        $request->render();

        $this->assertEquals(500, http_response_code());
        $this->assertEquals([
            'X-Foo: Bar',
            'NoKeyHeader',
        ], xdebug_get_headers());
    }
}