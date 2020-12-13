<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Stamp;

use Commanded\Core\Serializer\Serializable;

final class NamedStamp implements Stamp
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toPayload(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static($payload['name']);
    }
}
