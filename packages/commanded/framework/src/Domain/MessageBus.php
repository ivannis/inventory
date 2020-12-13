<?php

declare(strict_types=1);

namespace Commanded\Domain;

use Commanded\Core\Messaging\Command\Command;
use Commanded\Core\Messaging\CommandBus;
use Commanded\Core\Messaging\Event\Event;
use Commanded\Core\Messaging\EventBus;
use Commanded\Core\Messaging\Query\Query;
use Commanded\Core\Messaging\QueryBus;

class MessageBus
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;
    private EventBus $eventBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus, EventBus $eventBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->eventBus = $eventBus;
    }

    public function dispatch(Command $command, array $stamps = [])
    {
        $this->commandBus->dispatch($command, $stamps);
    }

    public function fire(Event $event, array $stamps = [])
    {
        $this->eventBus->dispatch($event, $stamps);
    }

    public function execute(Query $query)
    {
        return $this->queryBus->dispatch($query, []);
    }
}
