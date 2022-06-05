<?php

namespace Melody\Handlers;

use Melody\Interfaces\ErrorHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

abstract class AbstractErrorHandler implements ErrorHandlerInterface
{
    protected ServerRequestInterface $request;
    protected ResponseFactoryInterface $responseFactory;

    abstract public function handle(Throwable $throwable): ResponseInterface;

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function setResponseFactory(ResponseFactoryInterface $responseFactory): void
    {
        $this->responseFactory = $responseFactory;
    }
}
