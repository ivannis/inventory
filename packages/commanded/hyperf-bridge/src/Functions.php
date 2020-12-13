<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

if (!function_exists('lazyHandler')) {
    function lazyHandler(callable $resolver)
    {
        return function () use ($resolver) {
            return call_user_func($resolver(), ...func_get_args());
        };
    }
}