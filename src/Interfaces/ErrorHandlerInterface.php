<?php

namespace Melody\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ErrorHandlerInterface
{
    public function handle(Throwable $throwable): ResponseInterface;
}
