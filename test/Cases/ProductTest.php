<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use HyperfTest\HttpTestCase;
use Stock\Application\ProductService;
use Stock\Domain\ProductId;

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

        $service->create(ProductId::next(), 'Product 1');
        $service->create(ProductId::next(), 'Product 2');

        $this->get('/api/products');

        $this->assertCount(2, $this->response);
    }
}
