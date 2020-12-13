<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Query;

use Commanded\Core\Messaging\Envelope;
use Commanded\Core\Messaging\MessageNameTrait;
use Commanded\Core\Messaging\Query\Handler\QueryHandlerMap;

class QueryHandler
{
    use MessageNameTrait;
    private QueryHandlerMap $queryHandlerMap;

    public function __construct(QueryHandlerMap $queryHandlerMap)
    {
        $this->queryHandlerMap = $queryHandlerMap;
    }
    
    public function dispatch(Envelope $envelope)
    {
        $handler = $this->queryHandlerMap->getHandler($this->getMessageName($envelope));
        return call_user_func($handler, $envelope->message(), $envelope);
    }
}
