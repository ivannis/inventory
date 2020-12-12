<?php

declare(strict_types=1);

namespace Stock\Service;

use Carbon\Carbon;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\Utils\Collection;
use Stock\Exception\InsufficientProductQuantity;
use Stock\Exception\ProductNotFound;
use Stock\Exception\ProductOutOfStock;
use Stock\Model\InventoryHistory;
use Stock\Model\ProductStock;
use Stock\Model\StockItem;
use Webmozart\Assert\Assert;

class ProductStockService
{
    public function create(int $productId): ProductStock
    {
        $productStock = new ProductStock(['product_id' => $productId, 'quantity' => 0, 'valuation' => 0]);
        $productStock->save();

        return $productStock;
    }

    public function addItemToStock(int $productId, int $quantity, float $unitPrice, Carbon $date)
    {
        Assert::greaterThan($quantity, 0);
        Assert::greaterThan($unitPrice, 0);

        $productStock = $this->findOneByProductId($productId);

        // add stock item
        $productStock->stockItems()->save(new StockItem([
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'created_at' => $date,
        ]));

        // update stock
        $productStock->quantity += $quantity;
        $productStock->valuation += ($quantity * $unitPrice);
        $productStock->save();

        // track inventory
        $inventoryHistory = new InventoryHistory([
            'type' => 'purchased',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'product_id' => $productId,
            'created_at' => $date,
        ]);

        $inventoryHistory->save();
    }

    public function removeFromStock(int $productId, int $quantity, Carbon $date): float
    {
        Assert::greaterThan($quantity, 0);

        $productStock = $this->findOneByProductId($productId);
        $this->assertValidStock($productStock, $quantity);

        $total = 0;
        $remaining = $quantity;
        /** @var StockItem $stockItem */
        foreach ($productStock->stockItems()->orderBy('created_at')->get() as $stockItem) {
            if ($stockItem->hasStockSufficient($remaining)) {
                $stockItem->decrementStock($remaining);
                $total += $stockItem->calculatePrice($remaining);

                $stockItem->isOutOfStock() ? $stockItem->delete() : $stockItem->save();
                break;
            }

            // the item has $stockItem->quantity < $remaining
            $total += $stockItem->calculatePrice();
            $remaining -= $stockItem->onHand();
            $stockItem->delete();

            if ($remaining == 0) {
                break;
            }
        }

        // update stock
        $productStock->quantity -= $quantity;
        $productStock->valuation -= $total;

        $productStock->save();

        // track inventory
        $inventoryHistory = new InventoryHistory([
            'type' => 'application',
            'quantity' => -$quantity,
            'unit_price' => null,
            'product_id' => $productId,
            'created_at' => $date,
        ]);

        $inventoryHistory->save();

        return round($total, 2);
    }

    public function findStockItemsByProductId(int $productId): Collection
    {
        return $this->findOneByProductId($productId)->stockItems()->orderBy('created_at')->get();
    }

    public function findOneByProductId(int $productId): ProductStock
    {
        try {
            return ProductStock::query()->where(['product_id' => $productId])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw ProductNotFound::withId($productId);
        }
    }

    private function assertValidStock(ProductStock $productStock, int $quantity)
    {
        if ($productStock->quantity === 0) {
            throw new ProductOutOfStock('Product is out of stock.');
        }

        if ($productStock->quantity < $quantity) {
            throw new InsufficientProductQuantity('Insufficient product quantity.');
        }
    }
}
