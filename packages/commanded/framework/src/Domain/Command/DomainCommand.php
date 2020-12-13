<?php

declare(strict_types=1);

namespace Commanded\Domain\Command;

use Commanded\Core\Messaging\Command\Command;
use Commanded\Core\Messaging\NamedMessage;
use Commanded\Core\Utils\ClassUtils;

abstract class DomainCommand implements Command, NamedMessage
{
    public function messageName(): string
    {
        return defined('static::COMMAND_NAME') ? static::COMMAND_NAME : ClassUtils::messageName(static::class);
    }
}
