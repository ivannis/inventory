<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use HyperfTest\HttpTestCase;
use Stock\Service\ProductService;

/**
 * @internal
 * @coversNothing
 */
class ProductTest extends HttpTestCase
{
    use DatabaseTrait;

    public function testEmptyProducts()
    {
        $this->get('/api/products');

        $this->assertEmpty($this->response);
    }

    public function testProducts()
    {
        $service = $this->getContainer()->get(ProductService::class);

        $service->create('Product 1');
        $service->create('Product 2');

        $this->get('/api/products');

        $this->assertCount(2, $this->response);
        $this->assertEquals($this->response[0]['name'], 'Product 1');
        $this->assertEquals($this->response[1]['name'], 'Product 2');
    }
}
