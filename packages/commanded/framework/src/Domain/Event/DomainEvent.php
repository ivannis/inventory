<?php

declare(strict_types=1);

namespace Commanded\Domain\Event;

use Commanded\Core\Messaging\Event\Event;
use Commanded\Core\Messaging\NamedMessage;
use Commanded\Core\Serializer\Serializable;
use Commanded\Core\Utils\ClassUtils;

abstract class DomainEvent implements Event, NamedMessage, Serializable
{
    public function messageName(): string
    {
        return defined('static::EVENT_NAME') ? static::EVENT_NAME : ClassUtils::messageName(static::class);
    }
}
