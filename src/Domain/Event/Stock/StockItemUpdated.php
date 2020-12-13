<?php

declare(strict_types=1);

namespace Stock\Domain\Event\Stock;

use Commanded\Core\Serializer\Serializable;
use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\StockItemId;

final class StockItemUpdated extends DomainEvent
{
    public const EVENT_NAME = 'stock-item-updated';
    private StockItemId $id;
    private int $quantity;

    public function __construct(StockItemId $id, int $quantity)
    {
        $this->id = $id;
        $this->quantity = $quantity;
    }

    public function id(): StockItemId
    {
        return $this->id;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function toPayload(): array
    {
        return [
            'id' => (string) $this->id,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static(
            StockItemId::fromNative($payload['id']),
            $payload['quantity'],
        );
    }
}
