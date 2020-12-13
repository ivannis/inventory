<?php

declare(strict_types=1);

namespace Commanded\Core\Serializer;

interface Serializable
{
    public function toPayload(): array;

    public static function fromPayload(array $payload): Serializable;
}
