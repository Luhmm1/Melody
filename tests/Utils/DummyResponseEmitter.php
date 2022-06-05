<?php

namespace Melody\Tests\Utils;

use Melody\Interfaces\ResponseEmitterInterface;
use Psr\Http\Message\ResponseInterface;

class DummyResponseEmitter implements ResponseEmitterInterface
{
    public function emit(ResponseInterface $response): void
    {
        echo 'I\'m a dummy response emitter.';
    }
}
