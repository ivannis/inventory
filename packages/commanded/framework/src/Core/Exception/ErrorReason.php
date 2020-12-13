<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static ErrorReason BAD_REQUEST()
 * @method static ErrorReason UNAUTHORIZED()
 * @method static ErrorReason ACCESS_FORBIDDEN()
 * @method static ErrorReason NOT_FOUND()
 * @method static ErrorReason CONFLICT()
 * @method static ErrorReason TOO_MANY_REQUESTS()
 * @method static ErrorReason UNPROCESSABLE_ENTITY()
 * @method static ErrorReason SERVER_ERROR()
 * @method static ErrorReason UNKNOWN_ERROR()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
class ErrorReason extends Enum
{
    private const BAD_REQUEST = 'BAD_REQUEST';
    private const UNAUTHORIZED = 'UNAUTHORIZED';
    private const ACCESS_FORBIDDEN = 'ACCESS_FORBIDDEN';
    private const NOT_FOUND = 'NOT_FOUND';
    private const CONFLICT = 'CONFLICT';
    private const TOO_MANY_REQUESTS = 'TOO_MANY_REQUESTS';
    private const UNPROCESSABLE_ENTITY = 'UNPROCESSABLE_ENTITY';
    private const SERVER_ERROR = 'SERVER_ERROR';
    private const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
}
