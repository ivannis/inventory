<?php

declare(strict_types=1);

namespace Stock\Domain\ProcessManager;

use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Domain\MessageBus;
use Stock\Domain\Command\Stock\CreateStock;
use Stock\Domain\Event\Product\ProductCreated;
use Stock\Domain\StockId;

class ProductCreatedPM
{
    private MessageBus $bus;
    private CurrencyCode $currency;

    public function __construct(MessageBus $bus, CurrencyCode $currency)
    {
        $this->bus = $bus;
        $this->currency = $currency;
    }

    public function __invoke(ProductCreated $event)
    {
        $this->bus->dispatch(new CreateStock(StockId::next(), $event->id(), $this->currency));
    }
}
