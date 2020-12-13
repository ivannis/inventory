<?php

declare(strict_types=1);

namespace Stock\Domain\Exception;

use Commanded\Core\Exception\NotFoundException;
use Stock\Domain\ProductId;

final class ProductNotFound extends NotFoundException
{
    public function __construct(string $message = 'Product not found.', \Throwable $previous = null)
    {
        parent::__construct($message, ErrorReasons::PRODUCT_NOT_FOUND(), $previous);
    }

    public static function withId(ProductId $id): self
    {
        return new static(sprintf('Product with id %s not found.', (string) $id));
    }
}
