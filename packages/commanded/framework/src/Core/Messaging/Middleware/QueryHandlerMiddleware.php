<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Middleware;

use Commanded\Core\Messaging\Envelope;
use Commanded\Core\Messaging\Query\Query;
use Commanded\Core\Messaging\Query\QueryHandler;
use Commanded\Core\Messaging\Stamp\Stamps;

class QueryHandlerMiddleware implements MessageBusMiddleware
{
    private QueryHandler $queryHandler;

    public function __construct(QueryHandler $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    public function handle(Envelope $envelope, callable $next)
    {
        $query = $envelope->message();
        if (! $query instanceof Query) {
            throw new \Exception(
                sprintf(
                    'The message %s must be an instance of "%s"',
                    \get_class($query),
                    Query::class
                )
            );
        }

        return $this->queryHandler->dispatch(Envelope::clone($envelope));
    }
}
