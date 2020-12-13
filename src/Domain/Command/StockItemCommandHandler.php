<?php

declare(strict_types=1);

namespace Stock\Domain\Command;

use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\Command\Stock\CreateStockItem;
use Stock\Domain\Command\Stock\RemoveStockItem;
use Stock\Domain\Command\Stock\UpdateStockItem;
use Stock\Domain\Repository\StockItemRepository;
use Stock\Domain\StockItem;

final class StockItemCommandHandler
{
    private StockItemRepository $repository;

    public function __construct(StockItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DomainCommand $command)
    {
        switch ($command->messageName()) {
            case CreateStockItem::COMMAND_NAME:
                $this->onCreate($command);
                break;
            case UpdateStockItem::COMMAND_NAME:
                $this->onUpdateItem($command);
                break;
            case RemoveStockItem::COMMAND_NAME:
                $this->onRemoveItem($command);
                break;
            default:
                throw new \UnexpectedValueException(
                    'Unknown command ' . $command->messageName()
                );
        }
    }

    public function onCreate(CreateStockItem $command)
    {
        $stockItem = StockItem::fromId($command->id());
        $stockItem->create($command);

        $this->repository->create($stockItem);
    }

    public function onUpdateItem(UpdateStockItem $command)
    {
        $stockItem = $this->repository->load($command->id());
        $stockItem->update($command);

        $this->repository->save($stockItem);
    }

    public function onRemoveItem(RemoveStockItem $command)
    {
        $this->repository->delete($command->id());
    }
}
