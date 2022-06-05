<?php

namespace Melody\Tests\Utils;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DummyMiddleware implements MiddlewareInterface
{
    private string $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $response->getBody()->write($this->body);

        return $response;
    }
}
