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

use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Router\Router;
use Stock\Controller\InventoryController;
use Stock\Controller\ProductController;
use Stock\Controller\StockController;

Router::get('/', function (ResponseInterface $response) {
    return $response->json([
        'name' => 'Inventory API',
        'version' => 'v1.0',
    ]);
});

Router::addGroup('/api', function () {
    Router::addGroup('/products', function () {
        Router::get('', [ProductController::class, 'index']);
    });

    Router::addGroup('/stock', function () {
        Router::addGroup('/{productId}', function () {
            Router::post('/apply', [StockController::class, 'apply']);
            Router::post('/purchase', [StockController::class, 'purchase']);
            Router::get('/status', [StockController::class, 'status']);
            Router::get('/items', [StockController::class, 'items']);
        });
    });

    Router::get('/inventory/{productId}/history', [InventoryController::class, 'history']);
});
