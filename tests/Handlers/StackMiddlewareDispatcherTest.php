<?php

namespace Melody\Tests\Handlers;

use Melody\Handlers\StackMiddlewareDispatcher;
use Melody\Tests\Utils\DummyMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

class StackMiddlewareDispatcherTest extends TestCase
{
    public function testHandleWithMultipleMiddleware(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $dispatcher = new StackMiddlewareDispatcher($psr17);

        $dispatcher->addMiddleware(new DummyMiddleware('A'));
        $dispatcher->addMiddleware(new DummyMiddleware('B'));
        $dispatcher->addMiddleware(new DummyMiddleware('C'));
        $dispatcher->addMiddleware(new DummyMiddleware('D'));

        $response = $dispatcher->handle($request);

        self::assertSame('ABCD', (string) $response->getBody());
    }

    public function testMultipleHandleWithMultipleMiddleware(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $dispatcher = new StackMiddlewareDispatcher($psr17);

        $dispatcher->addMiddleware(new DummyMiddleware('A'));
        $dispatcher->addMiddleware(new DummyMiddleware('B'));
        $dispatcher->addMiddleware(new DummyMiddleware('C'));
        $dispatcher->addMiddleware(new DummyMiddleware('D'));

        $response1 = $dispatcher->handle($request);

        $dispatcher->addMiddleware(new DummyMiddleware('E'));
        $dispatcher->addMiddleware(new DummyMiddleware('F'));

        $response2 = $dispatcher->handle($request);

        self::assertSame('ABCD', (string) $response1->getBody());
        self::assertSame('ABCDEF', (string) $response2->getBody());
    }
}
