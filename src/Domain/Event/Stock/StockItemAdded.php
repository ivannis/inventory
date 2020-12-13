<?php

declare(strict_types=1);

namespace Stock\Domain\Event\Stock;

use Commanded\Core\Serializer\Serializable;
use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Core\ValueObject\Money\Money;
use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\StockId;
use Stock\Domain\StockItemId;

final class StockItemAdded extends DomainEvent
{
    public const EVENT_NAME = 'stock-item-added';
    private StockItemId $id;
    private StockId $stockId;
    private int $quantity;
    private Money $unitPrice;
    private DateTime $createdAt;

    public function __construct(
        StockItemId $id,
        StockId $stockId,
        int $quantity,
        Money $unitPrice,
        DateTime $createdAt
    ) {
        $this->id = $id;
        $this->stockId = $stockId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->createdAt = $createdAt;
    }

    public function id(): StockItemId
    {
        return $this->id;
    }

    public function stockId(): StockId
    {
        return $this->stockId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function toPayload(): array
    {
        return [
            'id' => (string) $this->id,
            'stockId' => (string) $this->stockId,
            'quantity' => $this->quantity,
            'unitPrice' => (float) $this->unitPrice->amount(),
            'currency' => (string) $this->unitPrice->currency(),
            'createdAt' => $this->createdAt()->toDateTimeString(),
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static(
            StockItemId::fromNative($payload['id']),
            StockId::fromNative($payload['stockId']),
            $payload['quantity'],
            Money::fromAmount(
                $payload['unitPrice'],
                CurrencyCode::fromNative($payload['currency'])
            ),
            DateTime::fromString($payload['createdAt'])
        );
    }
}
