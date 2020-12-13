<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Command;

use Commanded\Core\Messaging\Command\Handler\CommandHandlerMap;
use Commanded\Core\Messaging\Envelope;
use Commanded\Core\Messaging\MessageNameTrait;

class CommandHandler
{
    use MessageNameTrait;
    private CommandHandlerMap $commandHandlerMap;

    public function __construct(CommandHandlerMap $commandHandlerMap)
    {
        $this->commandHandlerMap = $commandHandlerMap;
    }

    public function dispatch(Envelope $envelope)
    {
        $handler = $this->commandHandlerMap->getHandler($this->getMessageName($envelope));
        call_user_func($handler, $envelope->message(), $envelope);
    }
}
