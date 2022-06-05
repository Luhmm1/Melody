<?php

namespace Melody\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareDispatcherInterface extends RequestHandlerInterface
{
    public function addMiddleware(MiddlewareInterface $middleware): void;

    public function handle(ServerRequestInterface $request): ResponseInterface;
}
