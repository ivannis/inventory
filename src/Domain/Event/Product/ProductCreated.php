<?php

declare(strict_types=1);

namespace Stock\Domain\Event\Product;

use Commanded\Core\Serializer\Serializable;
use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\ProductId;

final class ProductCreated extends DomainEvent
{
    public const EVENT_NAME = 'product-created';
    private ProductId $id;
    private string $name;

    public function __construct(ProductId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toPayload(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static(ProductId::fromNative($payload['id']), $payload['name']);
    }
}
