<?php

declare(strict_types=1);

namespace Commanded\Domain\Query;

use Commanded\Core\Messaging\NamedMessage;
use Commanded\Core\Messaging\Query\Query;
use Commanded\Core\Utils\ClassUtils;

abstract class DomainQuery implements Query, NamedMessage
{
    public function messageName(): string
    {
        return defined('static::QUERY_NAME') ? static::QUERY_NAME : ClassUtils::messageName(static::class);
    }
}
