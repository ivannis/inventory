<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Middleware;

use Commanded\Core\Messaging\Command\Command;
use Commanded\Core\Messaging\Command\CommandHandler;
use Commanded\Core\Messaging\Envelope;

class CommandHandlerMiddleware implements MessageBusMiddleware
{
    private CommandHandler $commandHandler;

    public function __construct(CommandHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    public function handle(Envelope $envelope, callable $next)
    {
        $command = $envelope->message();
        if (!$command instanceof Command) {
            throw new \Exception(
                sprintf(
                    'The message %s must be an instance of "%s"',
                    \get_class($command),
                    Command::class
                )
            );
        }

        $this->commandHandler->dispatch(Envelope::clone($envelope));

        $next($envelope);
    }
}
