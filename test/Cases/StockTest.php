<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use Carbon\Carbon;
use HyperfTest\HttpTestCase;
use Stock\Service\ProductService;
use Stock\Service\ProductStockService;

/**
 * @internal
 * @coversNothing
 */
class StockTest extends HttpTestCase
{
    use DatabaseTrait;

    public function testNoStock()
    {
        $this->get('/api/stock/1/status');

        $this->assertEquals($this->response['code'], 404);
        $this->assertEquals($this->response['message'], 'Product with id 1 not found.');
        $this->assertEquals($this->response['reason'], 'ProductNotFound');
    }

    public function testEmptyStock()
    {
        $products = $this->getContainer()->get(ProductService::class);
        $stock = $this->getContainer()->get(ProductStockService::class);

        $product = $products->create('Product 1');
        $stock->create($product->id);

        $this->get('/api/stock/1/status');

        $this->assertEquals($this->response['quantity'], 0);
        $this->assertEquals($this->response['valuation'], 0);
    }

    public function testValidStockWithItems()
    {
        $products = $this->getContainer()->get(ProductService::class);
        $stock = $this->getContainer()->get(ProductStockService::class);

        $product = $products->create('Product 1');
        $stock->create($product->id);

        $stock->addItemToStock($product->id, 10, 2.3, Carbon::createFromFormat('d/m/Y', '12/12/2020'));
        $stock->addItemToStock($product->id, 4, 5, Carbon::createFromFormat('d/m/Y', '12/12/2020'));

        $this->get('/api/stock/1/status');

        $this->assertEquals($this->response['quantity'], 14);
        $this->assertEquals($this->response['valuation'], 43);

        $this->get('/api/stock/1/items');

        $this->assertEquals($this->response[0]['quantity'], 10);
        $this->assertEquals($this->response[0]['unit_price'], 2.3);

        $this->assertEquals($this->response[1]['quantity'], 4);
        $this->assertEquals($this->response[1]['unit_price'], 5);
    }

    public function testPurchaseApplyStock()
    {
        $products = $this->getContainer()->get(ProductService::class);
        $stock = $this->getContainer()->get(ProductStockService::class);

        $product = $products->create('Product 1');
        $stock->create($product->id);

        $this->get('/api/stock/1/status');

        $this->assertEquals($this->response['quantity'], 0);
        $this->assertEquals($this->response['valuation'], 0);

        // Given many items were purchased
        $this->json('/api/stock/1/purchase', [
            'quantity' => 5,
            'unit_price' => 5,
            'date' => '10/12/2020',
        ]);

        $this->json('/api/stock/1/purchase', [
            'quantity' => 10,
            'unit_price' => 2.5,
            'date' => '01/12/2020',
        ]);

        // When try to apply and amount greater than the quantity
        $this->json('/api/stock/1/apply', [
            'quantity' => 30,
        ]);

        $this->assertEquals($this->response['code'], 400);
        $this->assertEquals($this->response['message'], 'Insufficient product quantity.');
        $this->assertEquals($this->response['reason'], 'InsufficientProductQuantity');

        // When apply a quantity it will take the first purchase products
        $this->json('/api/stock/1/apply', [
            'quantity' => 12,
        ]);

        $this->assertEquals($this->response['total'], 35);

        // And the stock must have only 3 items
        $this->get('/api/stock/1/status');

        $this->assertEquals($this->response['quantity'], 3);
        $this->assertEquals($this->response['valuation'], 15);

        // When apply a quantity equals to the stock quantity
        $this->json('/api/stock/1/apply', [
            'quantity' => 3,
        ]);

        $this->assertEquals($this->response['total'], 15);

        // When apply a quantity from an empty stock
        $this->json('/api/stock/1/apply', [
            'quantity' => 4,
        ]);

        $this->assertEquals($this->response['code'], 400);
        $this->assertEquals($this->response['message'], 'Product is out of stock.');
        $this->assertEquals($this->response['reason'], 'ProductOutOfStock');
    }

    public function testPurchaseApplyWithInvalidData()
    {
        $products = $this->getContainer()->get(ProductService::class);
        $stock = $this->getContainer()->get(ProductStockService::class);

        $product = $products->create('Product 1');
        $stock->create($product->id);

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
        $stock = $this->getContainer()->get(ProductStockService::class);

        $product = $products->create('Product 1');
        $stock->create($product->id);

        $this->json('/api/stock/1/purchase', [
            'quantity' => 1,
            'unit_price' => 10,
            'date' => '01/12/2020',
        ]);

        $this->json('/api/stock/1/purchase', [
            'quantity' => 2,
            'unit_price' => 20,
            'date' => '04/12/2020',
        ]);

        $this->json('/api/stock/1/purchase', [
            'quantity' => 2,
            'unit_price' => 15,
            'date' => '05/12/2020',
        ]);

        $this->json('/api/stock/1/apply', [
            'quantity' => 2,
        ]);

        $this->get('/api/stock/1/status');

        $this->assertEquals($this->response['quantity'], 3);
        $this->assertEquals($this->response['valuation'], 50);

        $this->get('/api/inventory/1/history');

        $this->assertEquals($this->response[0]['type'], 'purchased');
        $this->assertEquals($this->response[0]['quantity'], 1);
        $this->assertEquals($this->response[0]['unit_price'], 10);

        $this->assertEquals($this->response[1]['type'], 'purchased');
        $this->assertEquals($this->response[1]['quantity'], 2);
        $this->assertEquals($this->response[1]['unit_price'], 20);

        $this->assertEquals($this->response[2]['type'], 'purchased');
        $this->assertEquals($this->response[2]['quantity'], 2);
        $this->assertEquals($this->response[2]['unit_price'], 15);

        $this->assertEquals($this->response[3]['type'], 'application');
        $this->assertEquals($this->response[3]['quantity'], -2);
    }
}
