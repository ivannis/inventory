<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Domain\MessageBus;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Stock\Application\ProductService;
use Stock\Application\StockService;
use Stock\Domain\Command\Product\CreateProduct;
use Stock\Domain\Command\ProductCommandHandler;
use Stock\Domain\Command\Stock\AddStockItem;
use Stock\Domain\Command\Stock\ApplyQuantityToStock;
use Stock\Domain\Command\Stock\CreateStock;
use Stock\Domain\Command\Stock\CreateStockItem;
use Stock\Domain\Command\Stock\RemoveStockItem;
use Stock\Domain\Command\Stock\UpdateStockItem;
use Stock\Domain\Command\StockCommandHandler;
use Stock\Domain\Command\StockItemCommandHandler;
use Stock\Domain\Event\Product\ProductCreated;
use Stock\Domain\Event\Stock\StockItemAdded;
use Stock\Domain\Event\Stock\StockItemRemoved;
use Stock\Domain\Event\Stock\StockItemUpdated;
use Stock\Domain\Event\Stock\StockQuantityApplied;
use Stock\Domain\ProcessManager\ProductCreatedPM;
use Stock\Domain\ProcessManager\StockItemPM;
use Stock\Domain\Projector\InventoryHistoryProjector;
use Stock\Domain\Query\Product\FindAllProducts;
use Stock\Domain\Query\ProductQueryHandler;
use Stock\Domain\Query\Stock\FindItemsByProductId;
use Stock\Domain\Query\Stock\FindLastApplyMovementByProductId;
use Stock\Domain\Query\Stock\FindOneInventoryByProductId;
use Stock\Domain\Query\Stock\FindOneStockByProductId;
use Stock\Domain\Query\StockQueryHandler;
use Stock\Domain\Repository\InventoryHistoryQueryRepository;
use Stock\Domain\Repository\ProductQueryRepository;
use Stock\Domain\Repository\ProductRepository;
use Stock\Domain\Repository\StockItemQueryRepository;
use Stock\Domain\Repository\StockItemRepository;
use Stock\Domain\Repository\StockQueryRepository;
use Stock\Domain\Repository\StockRepository;
use Stock\Infrastructure\Console\InstallCommand;
use Stock\Infrastructure\Http\Controller\StockController;
use Stock\Infrastructure\Hyperf\Repository\HyperfInventoryHistoryQueryRepository;
use Stock\Infrastructure\Hyperf\Repository\HyperfProductQueryRepository;
use Stock\Infrastructure\Hyperf\Repository\HyperfProductRepository;
use Stock\Infrastructure\Hyperf\Repository\HyperfStockItemQueryRepository;
use Stock\Infrastructure\Hyperf\Repository\HyperfStockItemRepository;
use Stock\Infrastructure\Hyperf\Repository\HyperfStockQueryRepository;
use Stock\Infrastructure\Hyperf\Repository\HyperfStockRepository;

return [
    'command_handlers' => function(ContainerInterface $container) {
        return [
            CreateProduct::COMMAND_NAME => $container->get(ProductCommandHandler::class),
            CreateStock::COMMAND_NAME => $container->get(StockCommandHandler::class),
            AddStockItem::COMMAND_NAME => $container->get(StockCommandHandler::class),
            ApplyQuantityToStock::COMMAND_NAME => $container->get(StockCommandHandler::class),
            CreateStockItem::COMMAND_NAME => $container->get(StockItemCommandHandler::class),
            UpdateStockItem::COMMAND_NAME => $container->get(StockItemCommandHandler::class),
            RemoveStockItem::COMMAND_NAME => $container->get(StockItemCommandHandler::class),
        ];
    },
    'event_handlers' => function(ContainerInterface $container) {
        $productCreatedPM = $container->get(ProductCreatedPM::class);
        $stockItemPM = $container->get(StockItemPM::class);
        $historyProjector = $container->get(InventoryHistoryProjector::class);

        return [
            ProductCreated::EVENT_NAME => [
                // handler, priority
                [$productCreatedPM, 0],
            ],
            StockItemAdded::EVENT_NAME => [
                // handler, priority
                [$stockItemPM, 0],
                [$historyProjector, 0],
            ],
            StockItemUpdated::EVENT_NAME => [
                // handler, priority
                [$stockItemPM, 0],
            ],
            StockItemRemoved::EVENT_NAME => [
                // handler, priority
                [$stockItemPM, 0],
            ],
            StockQuantityApplied::EVENT_NAME => [
                // handler, priority
                [$historyProjector, 0],
            ],
        ];
    },
    'query_handlers' => function(ContainerInterface $container) {
        return [
            FindAllProducts::QUERY_NAME => $container->get(ProductQueryHandler::class),
            FindOneStockByProductId::QUERY_NAME => $container->get(StockQueryHandler::class),
            FindOneInventoryByProductId::QUERY_NAME => $container->get(StockQueryHandler::class),
            FindLastApplyMovementByProductId::QUERY_NAME => $container->get(StockQueryHandler::class),
            FindItemsByProductId::QUERY_NAME => $container->get(StockQueryHandler::class),
        ];
    },
    'default_currency' => function(ContainerInterface $container) {
        $config = $container->get(ConfigInterface::class);

        return CurrencyCode::fromNative($config->get('app_currency'));
    },
    ProductRepository::class => HyperfProductRepository::class,
    ProductQueryRepository::class => HyperfProductQueryRepository::class,
    StockRepository::class => HyperfStockRepository::class,
    StockQueryRepository::class => HyperfStockQueryRepository::class,
    StockItemRepository::class => HyperfStockItemRepository::class,
    StockItemQueryRepository::class => HyperfStockItemQueryRepository::class,
    InventoryHistoryQueryRepository::class => HyperfInventoryHistoryQueryRepository::class,
    ProductCreatedPM::class => function(ContainerInterface $container) {
        return lazyHandler(function () use ($container) {
            return new ProductCreatedPM(
                $container->get(MessageBus::class),
                $container->get('default_currency'),
            );
        });
    },
    StockItemPM::class => function(ContainerInterface $container) {
        return lazyHandler(function () use ($container) {
            return new StockItemPM(
                $container->get(MessageBus::class),
            );
        });
    },
    InstallCommand::class => function(ContainerInterface $container) {
        return new InstallCommand(
            $container->get(ProductService::class),
            $container->get(StockService::class),
            $container->get('default_currency'),
        );
    },
    StockController::class => function(ContainerInterface $container) {
        return new StockController(
            $container->get(StockService::class),
            $container->get('default_currency'),
        );
    },
];
