<?php

declare(strict_types=1);

namespace Stock\Domain\Event\Stock;

use Commanded\Core\Serializer\Serializable;
use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\StockId;

final class StockUpdated extends DomainEvent
{
    public const EVENT_NAME = 'stock-updated';
    private StockId $id;
    private int $quantity;
    private float $valuation;

    public function __construct(StockId $id, int $quantity, float $valuation)
    {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->valuation = $valuation;
    }

    public function id(): StockId
    {
        return $this->id;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function valuation(): float
    {
        return $this->valuation;
    }

    public function toPayload(): array
    {
        return [
            'id' => (string) $this->id,
            'quantity' => $this->quantity,
            'valuation' => $this->valuation,
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static(
            StockId::fromNative($payload['id']),
            $payload['quantity'],
            $payload['valuation'],
        );
    }
}
