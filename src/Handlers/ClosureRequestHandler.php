<?php

namespace Melody\Handlers;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClosureRequestHandler implements RequestHandlerInterface
{
    private Closure $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->closure)($request);
    }
}
