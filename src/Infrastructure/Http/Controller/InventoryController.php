<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Http\Controller;

use Hyperf\HttpServer\Contract\ResponseInterface;
use Stock\Application\StockService;
use Stock\Domain\ProductId;

class InventoryController
{
    private StockService $stocks;

    public function __construct(StockService $stocks)
    {
        $this->stocks = $stocks;
    }

    public function history(ResponseInterface $response, string $productId)
    {
        return $response->json($this->stocks->findInventoryByProductId(ProductId::fromNative($productId)));
    }
}
