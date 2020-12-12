<?php

declare(strict_types=1);

namespace Stock\Controller;

use Carbon\Carbon;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Stock\Controller\Request\ApplyProductRequest;
use Stock\Controller\Request\PurchaseProductRequest;
use Stock\Service\ProductStockService;

class StockController
{
    private ProductStockService $stock;

    public function __construct(ProductStockService $stock)
    {
        $this->stock = $stock;
    }

    public function purchase(PurchaseProductRequest $request, ResponseInterface $response, int $productId)
    {
        $quantity = (int) $request->input('quantity');
        $unitPrice = (float) $request->input('unit_price');
        $date = (string) $request->input('date', Carbon::now()->format('d/m/Y'));

        $this->stock->addItemToStock(
            $productId,
            $quantity,
            $unitPrice,
            Carbon::createFromFormat('d/m/Y', $date)
        );

        return $response->json('Ok');
    }

    public function apply(ApplyProductRequest $request, ResponseInterface $response, int $productId)
    {
        $quantity = (int) $request->input('quantity');

        return $response->json([
            'total' => $this->stock->removeFromStock($productId, $quantity, Carbon::now()),
        ]);
    }

    public function status(ResponseInterface $response, int $productId)
    {
        return $response->json($this->stock->findOneByProductId($productId)->only(
            'quantity',
            'valuation',
            'created_at',
        ));
    }

    public function items(ResponseInterface $response, int $productId)
    {
        return $response->json($this->stock->findStockItemsByProductId($productId)->toArray());
    }
}
