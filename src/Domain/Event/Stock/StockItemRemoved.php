<?php

declare(strict_types=1);

namespace Stock\Domain\Event\Stock;

use Commanded\Core\Serializer\Serializable;
use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\StockItemId;

final class StockItemRemoved extends DomainEvent
{
    public const EVENT_NAME = 'stock-item-removed';
    private StockItemId $id;
    private DateTime $removedAt;

    public function __construct(StockItemId $id, DateTime $removedAt)
    {
        $this->id = $id;
        $this->removedAt = $removedAt;
    }

    public function id(): StockItemId
    {
        return $this->id;
    }

    public function removedAt(): DateTime
    {
        return $this->removedAt;
    }

    public function toPayload(): array
    {
        return [
            'id' => (string) $this->id,
            'removedAt' => $this->removedAt()->toDateTimeString(),
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static(
            StockItemId::fromNative($payload['id']),
            DateTime::fromString($payload['removedAt'])
        );
    }
}
