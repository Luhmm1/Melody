<?php

namespace Melody\Tests;

use Melody\Application;
use Melody\Handlers\StackMiddlewareDispatcher;
use Melody\Tests\Utils\DummyErrorHandler;
use Melody\Tests\Utils\DummyMiddleware;
use Melody\Tests\Utils\DummyMiddlewareWithException;
use Melody\Tests\Utils\DummyResponseEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testAppWithQueueMiddlewareDispatcher(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $app = new Application($request, $psr17);

        $app->addMiddleware(new DummyMiddleware('A'));
        $app->addMiddleware(new DummyMiddleware('B'));
        $app->addMiddleware(new DummyMiddleware('C'));
        $app->addMiddleware(new DummyMiddleware('D'));

        $response = $app->handle($request);

        self::assertSame('DCBA', (string) $response->getBody());
    }

    public function testAppWithStackMiddlewareDispatcher(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $app = new Application($request, $psr17, new StackMiddlewareDispatcher($psr17));

        $app->addMiddleware(new DummyMiddleware('A'));
        $app->addMiddleware(new DummyMiddleware('B'));
        $app->addMiddleware(new DummyMiddleware('C'));
        $app->addMiddleware(new DummyMiddleware('D'));

        $response = $app->handle($request);

        self::assertSame('ABCD', (string) $response->getBody());
    }

    public function testAppWithDummyErrorHandler(): void
    {
        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $app = new Application($request, $psr17);

        // This error handler adds " (Really)" behind each error message.
        $app->setErrorHandler(new DummyErrorHandler());

        $app->addMiddleware(new DummyMiddlewareWithException('I am a flower.', 418));

        $response = $app->handle($request);

        self::assertSame('I am a flower. (Really)', (string) $response->getBody());
    }

    /**
     * @runInSeparateProcess
     */
    public function testAppWithDefaultResponseEmitter(): void
    {
        self::expectOutputString('DCBA');

        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $app = new Application($request, $psr17);

        $app->addMiddleware(new DummyMiddleware('A'));
        $app->addMiddleware(new DummyMiddleware('B'));
        $app->addMiddleware(new DummyMiddleware('C'));
        $app->addMiddleware(new DummyMiddleware('D'));

        $app->run();
    }

    /**
     * @runInSeparateProcess
     */
    public function testAppWithDummyResponseEmitter(): void
    {
        self::expectOutputString('I\'m a dummy response emitter.');

        $psr17 = new Psr17Factory();
        $request = $psr17->createServerRequest('GET', '/');

        $app = new Application($request, $psr17, null, new DummyResponseEmitter());

        $app->addMiddleware(new DummyMiddleware('A'));
        $app->addMiddleware(new DummyMiddleware('B'));
        $app->addMiddleware(new DummyMiddleware('C'));
        $app->addMiddleware(new DummyMiddleware('D'));

        $app->run();
    }
}
