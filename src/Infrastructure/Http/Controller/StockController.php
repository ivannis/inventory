<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Http\Controller;

use Carbon\Carbon;
use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Core\ValueObject\Money\Money;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Stock\Application\StockService;
use Stock\Domain\ProductId;
use Stock\Infrastructure\Http\Request\ApplyProductRequest;
use Stock\Infrastructure\Http\Request\PurchaseProductRequest;

class StockController
{
    private StockService $stock;
    private CurrencyCode $currency;

    public function __construct(StockService $stock, CurrencyCode $currency)
    {
        $this->stock = $stock;
        $this->currency = $currency;
    }

    public function purchase(PurchaseProductRequest $request, ResponseInterface $response, string $productId)
    {
        $quantity = (int) $request->input('quantity');
        $unitPrice = (float) $request->input('unit_price');
        $date = (string) $request->input('date', Carbon::now()->format('d/m/Y'));

        $this->stock->addItem(
            ProductId::fromNative($productId),
            $quantity,
            Money::fromAmount($unitPrice, $this->currency),
            DateTime::fromFormat('d/m/Y', $date)
        );

        return $response->json('Ok');
    }

    public function apply(ApplyProductRequest $request, ResponseInterface $response, string $productId)
    {
        $quantity = (int) $request->input('quantity');
        $this->stock->applyQuantity(
            ProductId::fromNative($productId),
            $quantity,
            DateTime::now()
        );

        $last = $this->stock->findLastApplyMovementByProductId(ProductId::fromNative($productId));
        return $response->json([
            'total' => $last['unitPrice'],
            'currency' => $last['currency'],
        ]);
    }

    public function status(ResponseInterface $response, string $productId)
    {
        return $response->json($this->stock->findOneByProductId(ProductId::fromNative($productId)));
    }

    public function items(ResponseInterface $response, string $productId)
    {
        return $response->json($this->stock->findItemsByProductId(ProductId::fromNative($productId)));
    }
}
