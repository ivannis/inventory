<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging;

interface NamedMessage extends Message
{
    public function messageName(): string;
}
