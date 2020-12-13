<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Money;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\ValueObject;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class Money extends AbstractValueObject
{
    public const DEFAULT_SCALE = 2;

    private string $amount;
    private CurrencyCode $currency;
    private int $scale = self::DEFAULT_SCALE;

    /**
     * Convenience factory method for a Money object.
     *
     * <code>
     * $fiveDollar = Money::USD(500);
     * </code>
     *
     * @return Money
     */
    public static function __callStatic(string $method, array $arguments)
    {
        return self::fromAmount(
            $arguments[0],
            CurrencyCode::fromNative($method),
            $arguments[1] ?? self::DEFAULT_SCALE
        );
    }

    public function __toString(): string
    {
        return \sprintf('%s %s', $this->amount->toNative(), $this->currency->toNative());
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function currency(): CurrencyCode
    {
        return $this->currency;
    }

    public static function fromAmount($amount, CurrencyCode $currency, int $scale = self::DEFAULT_SCALE): Money
    {
        //Properly initialize a bc number
        //@see https://github.com/php/php-src/pull/2746
        return new self([
            'amount' => self::numericValue($amount, $scale),
            'currency' => $currency->toNative(),
            'scale' => $scale,
        ]);
    }

    public function add(Money $addend): Money
    {
        $this->assertSameCurrencyAs($addend);

        $scale = $this->maxScale($addend);
        $amount = bcadd($this->amount, $addend->amount, $scale);

        return $this->newInstance($amount, $scale);
    }

    public function subtract(Money $subtrahend): Money
    {
        $this->assertSameCurrencyAs($subtrahend);

        $scale = $this->maxScale($subtrahend);
        $amount = bcsub($this->amount, $subtrahend->amount, $scale);

        return $this->newInstance($amount, $scale);
    }

    public function multiplyBy($multiplier, ?int $scale = null, bool $round = false): Money
    {
        $scale = $scale ?? $this->scale;
        if ($round) {
            ++$scale;
        }

        $multiplier = self::numericValue($multiplier, $scale);

        $amount = bcmul($this->amount, $multiplier, $scale);
        $money = $this->newInstance($amount, $scale);

        return $round ? $money->round($scale - 1) : $money;
    }

    public function divideBy($divisor, ?int $scale = null, bool $round = false): Money
    {
        $scale = $scale ?? $this->scale;
        if ($round) {
            ++$scale;
        }

        $divisor = self::numericValue($divisor, $scale);
        if (bccomp($divisor, '0', $scale) === 0) {
            throw new \InvalidArgumentException('Divisor cannot be 0.');
        }

        $amount = bcdiv($this->amount, $divisor, $scale);
        $money = $this->newInstance($amount, $scale);

        return $round ? $money->round($scale - 1) : $money;
    }

    public function round(int $scale = 0): Money
    {
        $add = '0.' . str_repeat('0', $scale) . '5';
        if ($this->isNegative()) {
            $add = '-' . $add;
        }

        $newAmount = bcadd($this->amount, $add, $scale);
        return $this->newInstance($newAmount, $scale);
    }

    public function hasSameCurrencyAs(Money $other): bool
    {
        return $this->currency->equals($other->currency);
    }

    public function equals(ValueObject $other): bool
    {
        return $this->compareTo($other) === 0;
    }

    public function greaterThan(Money $other): bool
    {
        return $this->compareTo($other) === 1;
    }

    public function greaterThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) >= 0;
    }

    public function lessThan(Money $other): bool
    {
        return $this->compareTo($other) === -1;
    }

    public function lessThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) <= 0;
    }

    public function isZero(): bool
    {
        return $this->compareToZero() === 0;
    }

    public function isPositive(): bool
    {
        return $this->compareToZero() === 1;
    }

    public function isNegative(): bool
    {
        return $this->compareToZero() === -1;
    }

    protected function init($value): void
    {
        Assert::isArray($value);

        Assert::keyExists($value, 'amount');
        Assert::keyExists($value, 'currency');
        Assert::string($value['amount']);

        $this->amount = $value['amount'];
        $this->currency = CurrencyCode::fromNative($value['currency']);
        $this->scale = $value['scale'] ?? self::DEFAULT_SCALE;
    }

    private function newInstance(string $amount, int $scale): Money
    {
        return new self([
            'amount' => $amount,
            'currency' => $this->currency->toNative(),
            'scale' => $scale,
        ]);
    }

    private function compareTo(Money $other): int
    {
        $this->assertSameCurrencyAs($other);

        return bccomp($this->amount, $other->amount, $this->maxScale($other));
    }

    private function compareToZero(): int
    {
        return bccomp($this->amount, '0', $this->scale);
    }

    private function maxScale(Money $other): int
    {
        return max($this->scale, $other->scale);
    }

    private function assertSameCurrencyAs(Money $other)
    {
        if (! $this->hasSameCurrencyAs($other)) {
            throw new \InvalidArgumentException('Currencies must be identical');
        }
    }

    private static function numericValue($amount, int $scale): string
    {
        Assert::numeric($amount);

        return is_float($amount) ?
            number_format($amount, $scale, '.', '') :
            bcadd((string) $amount, '0', $scale)
        ;
    }
}
