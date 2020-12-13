<?php

declare(strict_types=1);

namespace Stock\Domain\Event\Stock;

use Commanded\Core\Serializer\Serializable;
use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\ProductId;
use Stock\Domain\StockId;

final class StockCreated extends DomainEvent
{
    public const EVENT_NAME = 'stock-created';
    private StockId $id;
    private ProductId $productId;
    private CurrencyCode $currency;

    public function __construct(StockId $id, ProductId $productId, CurrencyCode $currency)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->currency = $currency;
    }

    public function id(): StockId
    {
        return $this->id;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function currency(): CurrencyCode
    {
        return $this->currency;
    }

    public function toPayload(): array
    {
        return [
            'id' => (string) $this->id,
            'productId' => (string) $this->productId,
            'currency' => (string) $this->currency,
        ];
    }

    public static function fromPayload(array $payload): Serializable
    {
        return new static(
            StockId::fromNative($payload['id']),
            ProductId::fromNative($payload['productId']),
            CurrencyCode::fromNative($payload['currency']),
        );
    }
}
