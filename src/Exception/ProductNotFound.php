<?php

declare(strict_types=1);

namespace Stock\Exception;

final class ProductNotFound extends BusinessException
{
    public static function withId(int $id): self
    {
        return new static(sprintf('Product with id %s not found.', $id), 'ProductNotFound', 404);
    }
}
