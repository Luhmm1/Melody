<?php

namespace Melody\Handlers;

use Melody\Interfaces\MiddlewareDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplStack;

class StackMiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var SplStack<MiddlewareInterface>
     */
    private SplStack $middlewareStack;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->middlewareStack = new SplStack();
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewareStack->push($middleware);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process(clone $this->middlewareStack)->handle($request);
    }

    /**
     * @param SplStack<MiddlewareInterface> $middlewareStack
     */
    private function process(SplStack $middlewareStack): RequestHandlerInterface
    {
        return new ClosureRequestHandler(
            function (ServerRequestInterface $request) use ($middlewareStack): ResponseInterface {
                if ($middlewareStack->isEmpty()) {
                    return $this->responseFactory->createResponse();
                }

                /** @var MiddlewareInterface $middleware */
                $middleware = $middlewareStack->pop();

                return $middleware->process($request, $this->process($middlewareStack));
            }
        );
    }
}
