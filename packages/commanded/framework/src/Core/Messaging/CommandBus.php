<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging;

use Commanded\Core\Assert\Assert;
use Commanded\Core\Messaging\Command\Command;

class CommandBus extends Bus
{
    public function dispatch(Message $message, array $stamps = [])
    {
        Assert::isInstanceOf($message, Command::class, sprintf(
            'The message %s must be an instance of "%s"',
            \get_class($message),
            Command::class
        ));

        call_user_func($this->callableForNextMiddleware(0), Envelope::wrap($message, $stamps));
    }
}
