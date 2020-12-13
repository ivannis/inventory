<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Query\Handler;

use Commanded\Core\Messaging\Query\Metadata\QueryHandlerMetadata;

class QueryHandlerMap
{
    private array $container = [];

    public function __construct(array $queryMap)
    {
        foreach ($queryMap as $queryName => $queryHandler) {
            $this->register($queryName, $queryHandler);
        }
    }

    public function getHandler(string $queryName): callable
    {
        if (!isset($this->container[$queryName])) {
            throw new \RuntimeException(
                sprintf(
                    'Could not find a query handler for query "%s"',
                    $queryName
                )
            );
        }

        return $this->container[$queryName];
    }

    private function register(string $queryName, callable $queryHandler)
    {
        $this->container[$queryName] = $queryHandler;
    }
}
