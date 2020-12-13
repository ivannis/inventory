<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging;

use Commanded\Core\Messaging\Stamp\Stamp;

interface MessageBus
{
    /**
     * @param Stamp[] $stamps
     */
    public function dispatch(Message $message, array $stamps);
}
