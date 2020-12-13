<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging;

use Commanded\Core\Messaging\Middleware\MessageBusMiddleware;

abstract class Bus implements MessageBus
{
    /** @var MessageBusMiddleware[] */
    private array $middlewares = [];

    public function __construct(MessageBusMiddleware ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    protected function callableForNextMiddleware($index): callable
    {
        if (! isset($this->middlewares[$index])) {
            return function (Envelope $envelope) {
                return $envelope;
            };
        }

        $middleware = $this->middlewares[$index];
        return function (Envelope $envelope) use ($middleware, $index) {
            return $middleware->handle($envelope, $this->callableForNextMiddleware($index + 1));
        };
    }
}
