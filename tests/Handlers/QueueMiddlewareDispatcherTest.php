<?php

namespace Melody\Tests\Handlers;

use Melody\Handlers\QueueMiddlewareDispatcher;
use Melody\Tests\Utils\DummyMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

class QueueMiddlewareDispatcherTest extends TestCase
{
    public function testHandleWithMultipleMiddleware(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $dispatcher = new QueueMiddlewareDispatcher($psr17);

        $dispatcher->addMiddleware(new DummyMiddleware('A'));
        $dispatcher->addMiddleware(new DummyMiddleware('B'));
        $dispatcher->addMiddleware(new DummyMiddleware('C'));
        $dispatcher->addMiddleware(new DummyMiddleware('D'));

        $response = $dispatcher->handle($request);

        self::assertSame('DCBA', (string) $response->getBody());
    }

    public function testMultipleHandleWithMultipleMiddleware(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $dispatcher = new QueueMiddlewareDispatcher($psr17);

        $dispatcher->addMiddleware(new DummyMiddleware('A'));
        $dispatcher->addMiddleware(new DummyMiddleware('B'));
        $dispatcher->addMiddleware(new DummyMiddleware('C'));
        $dispatcher->addMiddleware(new DummyMiddleware('D'));

        $response1 = $dispatcher->handle($request);

        $dispatcher->addMiddleware(new DummyMiddleware('E'));
        $dispatcher->addMiddleware(new DummyMiddleware('F'));

        $response2 = $dispatcher->handle($request);

        self::assertSame('DCBA', (string) $response1->getBody());
        self::assertSame('FEDCBA', (string) $response2->getBody());
    }
}
