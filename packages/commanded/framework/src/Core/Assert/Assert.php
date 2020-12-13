<?php

declare(strict_types=1);

namespace Commanded\Core\Assert;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Commanded\Core\Exception\InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

class Assert extends Assertion
{
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function alwaysValid($value, $message = '')
    {
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function alwaysInvalid($value, $message = '')
    {
        static::reportInvalidArgument(\sprintf(
            $message ?: 'Value "%s" is always invalid.',
            static::valueToString($value)
        ));
    }

    public static function isValid(callable $assert): bool
    {
        try {
            $assert();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Assert that value is a php float.
     *
     * @param mixed $value
     * @param null|string $message
     */
    public static function float($value, $message = '')
    {
        $result = \filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($result !== false) {
            return;
        }

        if (\is_numeric($value) && \is_infinite($value)) {
            return;
        }

        static::reportInvalidArgument(\sprintf(
            $message ?: 'Expected a float. Got: %s',
            static::typeToString($value)
        ));
    }

    /**
     * Assert that value is a url.
     *
     * @param mixed $value
     * @param null|string $message
     */
    public static function url($value, $message = '')
    {
        static::string($value, $message);

        $protocols = ['http', 'https'];

        $pattern = '~^
            (%s)://                                                             # protocol
            (([\.\pL\pN-]+:)?([\.\pL\pN-]+)@)?                                  # basic auth
            (
                ([\pL\pN\pS\-\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?)          # a domain name
                |                                                               # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                              # an IP address
                |                                                               # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]                                                              # an IPv6 address
            )
            (:[0-9]+)?                                                          # a port (optional)
            (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*          # a path
            (?:\? (?:[\pL\pN\-._\~!$&\'\[\]()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a query (optional)
            (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?       # a fragment (optional)
        $~ixu';

        $pattern = \sprintf($pattern, \implode('|', $protocols));

        if (! \preg_match($pattern, $value)) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" was expected to be a valid URL starting with http or https',
                static::valueToString($value)
            ));
        }
    }

    /**
     * Assert that value is a url scheme.
     *
     * @param mixed $value
     * @param null|string $message
     */
    public static function urlScheme($value, $message = '')
    {
        if (\preg_match('/^[a-z]([a-z0-9\+\.-]+)?$/i', $value) === 0) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" is not a url scheme.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function hostName($value, $message = '')
    {
        try {
            // valid chars check
            static::regex(
                $value,
                '/^([a-z\\d](-*[a-z\\d])*)(\\.([a-z\\d](-*[a-z\\d])*))*$/i',
                $message
            );

            // overall length check
            static::regex($value, '/^.{1,253}$/', $message);

            // length of each label
            static::regex($value, '/^[^\\.]{1,63}(\\.[^\\.]{1,63})*$/', $message);
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid hostname.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function urlPath($value, $message = '')
    {
        $filteredValue = parse_url($value, PHP_URL_PATH);
        if ($filteredValue === null || strlen($filteredValue) != strlen($value)) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid path.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function port($value, $message = '')
    {
        try {
            static::integer($value, $message);
            static::range($value, 0, 65535, $message);
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid port.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function queryString($value, $message = '')
    {
        try {
            static::regex($value, '/^\\?([\\w\\.\\-[\\]~&%+]+(=([\\w\\.\\-~&%+]+)?)?)*$/', $message);
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid query string.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function urlFragment($value, $message = '')
    {
        try {
            static::regex($value, "/^#[?%!$&'()*+,;=a-zA-Z0-9-._~:@\/]*$/", $message);
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid url fragment.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function timezone($value, $message = '')
    {
        try {
            static::string($value, $message);
            static::oneOf($value, timezone_identifiers_list(), $message);
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid timezone.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|callable|string $message
     */
    public static function isResource($value, string $type = null, $message = '')
    {
        if (! \is_resource($value)) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" is not a resource.',
                static::valueToString($value)
            ));
        }

        if ($type && $type !== get_resource_type($value)) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a resource of type %s.',
                static::valueToString($value),
                static::typeToString($type)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function noWhitespace($value, $message = '')
    {
        if (preg_match('#\s#', $value)) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" was not expected to have whitespace.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function latitude($value, $message = '')
    {
        try {
            static::regex(
                $value,
                '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/',
                $message
            );
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid latitude.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     * @param null|string $message
     */
    public static function longitude($value, $message = '')
    {
        try {
            static::regex(
                $value,
                '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/',
                $message
            );
        } catch (InvalidArgumentException $e) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value "%s" expected to be a valid longitude.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * Exclusive range, so Assert::(3, 3, 5) not passes.
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @param string $message
     */
    public static function rangeExclusive($value, $min, $max, $message = '')
    {
        if ($value <= $min || $value >= $max) {
            static::reportInvalidArgument(\sprintf(
                $message ?: 'Expected a value lower than %2$s and greater than %3$s. Got: %s',
                static::valueToString($value),
                static::valueToString($min),
                static::valueToString($max)
            ));
        }
    }

    /**
     * @param string $value
     * @param string|null $country
     * @param bool $strict
     * @param string $message
     */
    public static function phoneNumber($value, $country = null, $strict = true, $message = '')
    {
        $validator = PhoneNumberUtil::getInstance();
        $isValidNumber = true;

        try {
            $number = $validator->parse($value, $country);
        } catch (NumberParseException $e) {
            $isValidNumber = false;
        }

        if ($strict && $isValidNumber && !$validator->isValidNumber($number)) {
            $isValidNumber = false;
        }

        if (!$isValidNumber) {
            if ($country !== null) {
                static::reportInvalidArgument(\sprintf(
                    $message ?: 'Value %s expected to be a valid %s phone number.',
                    static::valueToString($value),
                    static::valueToString($country)
                ));
            }

            static::reportInvalidArgument(\sprintf(
                $message ?: 'Value %s expected to be a valid phone number.',
                static::valueToString($value)
            ));
        }
    }

    /**
     * @param mixed $value
     */
    public static function stringify($value): string
    {
        return static::valueToString($value);
    }
}
