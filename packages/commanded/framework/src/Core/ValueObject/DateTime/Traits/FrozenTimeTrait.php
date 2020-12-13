<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use DateTimeInterface;

trait FrozenTimeTrait
{
    use RelativeKeywordTrait;

    public function modify(string $relative): self
    {
        if (preg_match('/hour|minute|second/', $relative)) {
            return $this;
        }

        $new = $this->toNative()->modify($relative);
        if ($new->format('H:i:s') !== '00:00:00') {
            $new = $new->setTime(0, 0, 0);
        }

        return static::fromDateTime($new);
    }

    public function setDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second = 0): self
    {
        return static::fromDateTime(
            $this->setDate($year, $month, $day)->toNative()->setTime(0, 0, 0)
        );
    }

    public function setTime(int $hour, int $minute, int $second = 0): self
    {
        return static::fromDateTime(
            $this->toNative()->setTime(0, 0, 0)
        );
    }

    public function withTimezone(string $timezone): self
    {
        return $this;
    }

    public function setTimestamp(int $value): self
    {
        return static::fromDateTime(
            $this->toNative()->setTimestamp($value)->setTime(0, 0, 0)
        );
    }

    /**
     * Removes the time components from an input string.
     *
     * Used to ensure constructed objects always lack time.
     *
     * @param \DateTimeInterface|int|string $time The input time. Integer values will be assumed
     *                                            to be in UTC. The 'now' and '' values will use the current local time.
     * @return string the date component of $time
     */
    protected static function stripTime($time)
    {
        if (is_int($time) || ctype_digit($time)) {
            return gmdate('Y-m-d 00:00:00', $time);
        }

        if ($time instanceof DateTimeInterface) {
            $time = $time->format('Y-m-d 00:00:00');
        }

        if (substr($time, 0, 1) === '@') {
            return gmdate('Y-m-d', (int) str_replace('-', '', substr($time, 1))) . ' 00:00:00';
        }

        if ($time === null || $time === 'now' || $time === '') {
            return date('Y-m-d 00:00:00');
        }

        if (static::hasRelativeKeywords($time)) {
            return date('Y-m-d 00:00:00', strtotime($time));
        }

        return preg_replace('/\d{1,2}:\d{1,2}:\d{1,2}(?:\.\d+)?/', '00:00:00', $time);
    }

    /**
     * Remove time components from strtotime relative strings.
     *
     * @param string $time The input expression
     * @return string the output expression with no time modifiers
     */
    protected static function stripRelativeTime($time)
    {
        return preg_replace('/([-+]\s*\d+\s(?:minutes|seconds|hours|microseconds))/', '', $time);
    }
}
