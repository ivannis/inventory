<?php

declare(strict_types=1);

namespace Stock\Domain\Exception;

use Commanded\Core\Exception\ErrorReason;

/**
 * @method static ErrorReasons PRODUCT_NOT_FOUND()
 * @method static ErrorReasons INSUFFICIENT_PRODUCT_QUANTITY()
 * @method static ErrorReasons PRODUCT_OUT_OF_STOCK()
 */
class ErrorReasons extends ErrorReason
{
    private const PRODUCT_NOT_FOUND = 'PRODUCT_NOT_FOUND';
    private const INSUFFICIENT_PRODUCT_QUANTITY = 'INSUFFICIENT_PRODUCT_QUANTITY';
    private const PRODUCT_OUT_OF_STOCK = 'PRODUCT_OUT_OF_STOCK';
}