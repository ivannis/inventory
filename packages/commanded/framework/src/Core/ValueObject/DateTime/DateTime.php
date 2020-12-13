<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use DateTimeImmutable;
use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\DateTime\Traits\ComparisonTrait;
use Commanded\Core\ValueObject\DateTime\Traits\DifferenceTrait;
use Commanded\Core\ValueObject\DateTime\Traits\FormattingTrait;
use Commanded\Core\ValueObject\DateTime\Traits\InstanceTrait;
use Commanded\Core\ValueObject\DateTime\Traits\ModifierTrait;
use Commanded\Core\ValueObject\DateTime\Traits\PropertyTrait;
use Commanded\Core\ValueObject\DateTime\Traits\RelativeKeywordTrait;
use Commanded\Core\ValueObject\DateTime\Traits\TestingAidTrait;

/**
 * @method static static fromNative(DateTimeImmutable $value)
 * @method DateTimeImmutable toNative()
 * @method static DateTimeInterface now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE)
 */
class DateTime extends AbstractValueObject implements DateTimeInterface
{
    use ComparisonTrait;
    use DifferenceTrait;
    use InstanceTrait;
    use FormattingTrait;
    use PropertyTrait;
    use ModifierTrait;
    use TestingAidTrait;
    use RelativeKeywordTrait;
    
    protected static string $toStringFormat = DateTimeInterface::DEFAULT_TO_STRING_FORMAT;

    protected function init($value): void
    {
        Assert::isInstanceOf($value, \DateTimeImmutable::class);
    }

    protected static function instance(string $time = 'now', string $timezone = DateTimeInterface::DEFAULT_TIMEZONE)
    {
        $timezone = static::createTimeZone($timezone);

        static::$_lastErrors = [];
        if (! static::hasTestNow()) {
            return static::fromNative(
                new DateTimeImmutable($time === null ? 'now' : $time, $timezone)
            );
        }

        $testNow = static::getTestNow();
        $relative = static::hasRelativeKeywords($time);
        if (! empty($time) && $time !== 'now' && ! $relative) {
            return static::fromNative(
                new DateTimeImmutable($time, $timezone)
            );
        }

        $testNow = clone $testNow;
        if ($relative) {
            $testNow = $testNow->modify($time);
        }

        $relativeTime = static::isTimeExpression($time);
        if (! $relativeTime && $timezone !== $testNow->timezone()->toNative()) {
            $testNow = $testNow->withTimezone($timezone->getName());
        }

        $time = $testNow->format('Y-m-d H:i:s.u');

        return static::fromNative(new DateTimeImmutable($time, $timezone));
    }
}
