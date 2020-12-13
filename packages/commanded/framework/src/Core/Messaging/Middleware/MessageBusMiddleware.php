<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Middleware;

use Commanded\Core\Messaging\Envelope;

interface MessageBusMiddleware
{
    public function handle(Envelope $envelope, callable $next);
}
