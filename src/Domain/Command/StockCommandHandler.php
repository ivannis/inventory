<?php

declare(strict_types=1);

namespace Stock\Domain\Command;

use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\Command\Stock\AddStockItem;
use Stock\Domain\Command\Stock\ApplyQuantityToStock;
use Stock\Domain\Command\Stock\CreateStock;
use Stock\Domain\Repository\StockItemQueryRepository;
use Stock\Domain\Stock;
use Stock\Domain\Repository\StockRepository;

final class StockCommandHandler
{
    private StockRepository $repository;
    private StockItemQueryRepository $items;

    public function __construct(StockRepository $repository, StockItemQueryRepository $items)
    {
        $this->repository = $repository;
        $this->items = $items;
    }

    public function __invoke(DomainCommand $command)
    {
        switch ($command->messageName()) {
            case CreateStock::COMMAND_NAME:
                $this->onCreate($command);
                break;
            case AddStockItem::COMMAND_NAME:
                $this->onAddItem($command);
                break;
            case ApplyQuantityToStock::COMMAND_NAME:
                $this->onApplyQuantity($command);
                break;
            default:
                throw new \UnexpectedValueException(
                    'Unknown command ' . $command->messageName()
                );
        }
    }

    public function onCreate(CreateStock $command)
    {
        $stock = Stock::fromId($command->id());
        $stock->create($command);

        $this->repository->create($stock);
    }

    public function onAddItem(AddStockItem $command)
    {
        $stock = $this->repository->load($command->stockId());
        $stock->addStockItem($command);

        $this->repository->save($stock);
    }

    public function onApplyQuantity(ApplyQuantityToStock $command)
    {
        $stock = $this->repository->load($command->stockId());
        $items = $this->items->findByStockId($command->stockId());

        $stock->applyQuantityToStock($command, $items);

        $this->repository->save($stock);
    }
}
