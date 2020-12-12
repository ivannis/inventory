<?php

declare(strict_types=1);

namespace Stock\Controller;

use Hyperf\HttpServer\Contract\ResponseInterface;
use Stock\Service\InventoryService;

class InventoryController
{
    private InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->inventory = $inventory;
    }

    public function history(ResponseInterface $response, int $productId)
    {
        return $response->json($this->inventory->findHistoryByProductId($productId)->toArray());
    }
}
