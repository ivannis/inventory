<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Http\Controller;

use Hyperf\HttpServer\Contract\ResponseInterface;
use Stock\Application\ProductService;

class ProductController
{
    private ProductService $product;

    public function __construct(ProductService $product)
    {
        $this->product = $product;
    }

    public function index(ResponseInterface $response)
    {
        return $response->json($this->product->findAll()->toArray());
    }
}
