<?php

namespace Melody\Tests\Utils;

use Melody\Handlers\AbstractErrorHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class DummyErrorHandler extends AbstractErrorHandler
{
    public function handle(Throwable $throwable): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($throwable->getMessage() . ' (Really)');

        return $response;
    }
}
