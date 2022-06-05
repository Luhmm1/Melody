<?php

namespace Melody;

use Melody\Handlers\AbstractErrorHandler;
use Melody\Handlers\QueueMiddlewareDispatcher;
use Melody\Interfaces\ErrorHandlerInterface;
use Melody\Interfaces\MiddlewareDispatcherInterface;
use Melody\Interfaces\ResponseEmitterInterface;
use Melody\Middleware\ErrorHandlerMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class Application
{
    private ServerRequestInterface $request;
    private ResponseFactoryInterface $responseFactory;
    private MiddlewareDispatcherInterface $middlewareDispatcher;
    private ResponseEmitterInterface $responseEmitter;

    public function __construct(
        ServerRequestInterface $request,
        ResponseFactoryInterface $responseFactory,
        ?MiddlewareDispatcherInterface $middlewareDispatcher = null,
        ?ResponseEmitterInterface $responseEmitter = null
    ) {
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->middlewareDispatcher = $middlewareDispatcher ?? new QueueMiddlewareDispatcher($responseFactory);
        $this->responseEmitter = $responseEmitter ?? new ResponseEmitter();
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewareDispatcher->addMiddleware($middleware);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareDispatcher->handle($request);
    }

    public function setErrorHandler(ErrorHandlerInterface $errorHandler): void
    {
        if ($errorHandler instanceof AbstractErrorHandler) {
            $errorHandler->setRequest($this->request);
            $errorHandler->setResponseFactory($this->responseFactory);
        }

        $this->addMiddleware(new ErrorHandlerMiddleware($errorHandler));
    }

    public function run(): void
    {
        $response = $this->handle($this->request);

        $this->responseEmitter->emit($response);
    }
}
