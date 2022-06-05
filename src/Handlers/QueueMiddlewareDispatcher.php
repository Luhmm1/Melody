<?php

namespace Melody\Handlers;

use Melody\Interfaces\MiddlewareDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplQueue;

class QueueMiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var SplQueue<MiddlewareInterface>
     */
    private SplQueue $middlewareQueue;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->middlewareQueue = new SplQueue();
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewareQueue->enqueue($middleware);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process(clone $this->middlewareQueue)->handle($request);
    }

    /**
     * @param SplQueue<MiddlewareInterface> $middlewareQueue
     */
    private function process(SplQueue $middlewareQueue): RequestHandlerInterface
    {
        return new ClosureRequestHandler(
            function (ServerRequestInterface $request) use ($middlewareQueue): ResponseInterface {
                if ($middlewareQueue->isEmpty()) {
                    return $this->responseFactory->createResponse();
                }

                /** @var MiddlewareInterface $middleware */
                $middleware = $middlewareQueue->dequeue();

                return $middleware->process($request, $this->process($middlewareQueue));
            }
        );
    }
}
