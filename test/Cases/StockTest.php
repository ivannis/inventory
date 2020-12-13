<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use Carbon\Carbon;
use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Core\ValueObject\Money\Money;
use HyperfTest\HttpTestCase;
use Stock\Application\ProductService;
use Stock\Application\StockService;
use Stock\Domain\ProductId;

/**
 * @internal
 * @coversNothing
 */
class StockTest extends HttpTestCase
{
    use DatabaseTrait;

    public function testEmptyStock()
    {
        $products = $this->getContainer()->get(ProductService::class);

        $productId = ProductId::next();
        $products->create($productId, 'Product 1');

        $this->get(sprintf('/api/stock/%s/status', (string) $productId));

        $this->assertEquals($this->response['quantity'], 0);
        $this->assertEquals($this->response['valuation'], 0);
    }

    public function testValidStockWithItems()
    {
        $products = $this->getContainer()->get(ProductService::class);
        $stock = $this->getContainer()->get(StockService::class);

        $productId = ProductId::next();
        $products->create($productId, 'Product 1');

        $stock->addItem($productId, 10, Money::fromAmount(2.3, CurrencyCode::NZD()), DateTime::fromFormat('d/m/Y', '12/12/2020'));
        $stock->addItem($productId, 4, Money::fromAmount(5, CurrencyCode::NZD()), DateTime::fromFormat('d/m/Y', '12/12/2020'));

        $this->get(sprintf('/api/stock/%s/status', (string) $productId));

        $this->assertEquals($this->response['quantity'], 14);
        $this->assertEquals($this->response['valuation'], 43);

        $this->get(sprintf('/api/stock/%s/items', (string) $productId));

        $this->assertEquals($this->response[0]['quantity'], 4);
        $this->assertEquals($this->response[0]['unitPrice'], 5);

        $this->assertEquals($this->response[1]['quantity'], 10);
        $this->assertEquals($this->response[1]['unitPrice'], 2.3);
    }

    public function testPurchaseApplyStock()
    {
        $products = $this->getContainer()->get(ProductService::class);

        $productId = ProductId::next();
        $products->create($productId, 'Product 1');

        $this->get(sprintf('/api/stock/%s/status', (string) $productId));

        $this->assertEquals($this->response['quantity'], 0);
        $this->assertEquals($this->response['valuation'], 0);

        // Given many items were purchased
        $this->json(sprintf('/api/stock/%s/purchase', (string) $productId), [
            'quantity' => 5,
            'unit_price' => 5,
            'date' => '10/12/2020',
        ]);

        $this->json(sprintf('/api/stock/%s/purchase', (string) $productId), [
            'quantity' => 10,
            'unit_price' => 2.5,
            'date' => '01/12/2020',
        ]);

        // When try to apply and amount greater than the quantity
        $this->json(sprintf('/api/stock/%s/apply', (string) $productId), [
            'quantity' => 30,
        ]);

        $this->assertEquals($this->response['code'], 422);
        $this->assertEquals($this->response['message'], 'Insufficient product quantity.');
        $this->assertEquals($this->response['reason'], 'INSUFFICIENT_PRODUCT_QUANTITY');

        // When apply a quantity it will take the first purchase products
        $this->json(sprintf('/api/stock/%s/apply', (string) $productId), [
            'quantity' => 12,
        ]);

        $this->assertEquals($this->response['total'], 35);
        $this->assertEquals($this->response['currency'], 'NZD');

        // And the stock must have only 3 items
        $this->get(sprintf('/api/stock/%s/status', (string) $productId));

        $this->assertEquals($this->response['quantity'], 3);
        $this->assertEquals($this->response['valuation'], 15);

        // When apply a quantity equals to the stock quantity
        $this->json(sprintf('/api/stock/%s/apply', (string) $productId), [
            'quantity' => 3,
        ]);

        // And apply a quantity from an empty stock
        $this->json(sprintf('/api/stock/%s/apply', (string) $productId), [
            'quantity' => 4,
        ]);

        $this->assertEquals($this->response['code'], 422);
        $this->assertEquals($this->response['message'], 'Product is out of stock.');
        $this->assertEquals($this->response['reason'], 'PRODUCT_OUT_OF_STOCK');
    }

    public function testPurchaseApplyWithInvalidData()
    {
        $products = $this->getContainer()->get(ProductService::class);

        $productId = ProductId::next();
        $products->create($productId, 'Product 1');

        // Invalid purchase
        $this->json('/api/stock/1/purchase', [
            'quantity' => 'asd',
            'unit_price' => null,
            'date' => '3',
        ]);

        $this->assertEquals($this->response['code'], 422);
        $this->assertEquals($this->response['message'], 'Bad request');
        $this->assertEquals($this->response['reason'], 'UNPROCESSABLE_ENTITY');
        $this->assertEquals($this->response['errors']['quantity'], 'The quantity must be an integer.');
        $this->assertEquals($this->response['errors']['unit_price'], 'The unit price field is required.');
        $this->assertEquals($this->response['errors']['date'], 'The date does not match the format d/m/Y.');

        $this->json('/api/stock/1/apply', [
            'quantity' => 34.2,
        ]);

        $this->assertEquals($this->response['code'], 422);
        $this->assertEquals($this->response['message'], 'Bad request');
        $this->assertEquals($this->response['reason'], 'UNPROCESSABLE_ENTITY');
        $this->assertEquals($this->response['errors']['quantity'], 'The quantity must be an integer.');
    }

    public function testHistory()
    {
        $products = $this->getContainer()->get(ProductService::class);

        $productId = ProductId::next();
        $products->create($productId, 'Product 1');

        $this->json(sprintf('/api/stock/%s/purchase', (string) $productId), [
            'quantity' => 1,
            'unit_price' => 10,
            'date' => '01/12/2020',
        ]);

        $this->json(sprintf('/api/stock/%s/purchase', (string) $productId), [
            'quantity' => 2,
            'unit_price' => 20,
            'date' => '04/12/2020',
        ]);

        $this->json(sprintf('/api/stock/%s/purchase', (string) $productId), [
            'quantity' => 2,
            'unit_price' => 15,
            'date' => '05/12/2020',
        ]);

        $this->json(sprintf('/api/stock/%s/apply', (string) $productId), [
            'quantity' => 2,
        ]);

        $this->get(sprintf('/api/stock/%s/status', (string) $productId));

        $this->assertEquals($this->response['quantity'], 3);
        $this->assertEquals($this->response['valuation'], 50);

        $this->get(sprintf('/api/inventory/%s/history', (string) $productId));

        $this->assertEquals($this->response[0]['type'], 'purchased');
        $this->assertEquals($this->response[0]['quantity'], 1);
        $this->assertEquals($this->response[0]['unitPrice'], 10);

        $this->assertEquals($this->response[1]['type'], 'purchased');
        $this->assertEquals($this->response[1]['quantity'], 2);
        $this->assertEquals($this->response[1]['unitPrice'], 20);

        $this->assertEquals($this->response[2]['type'], 'purchased');
        $this->assertEquals($this->response[2]['quantity'], 2);
        $this->assertEquals($this->response[2]['unitPrice'], 15);

        $this->assertEquals($this->response[3]['type'], 'application');
        $this->assertEquals($this->response[3]['quantity'], -2);
    }
}
