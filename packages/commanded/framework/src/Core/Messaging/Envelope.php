<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging;

use Commanded\Core\Messaging\Stamp\Stamp;

class Envelope
{
    /** @var Stamp[][] */
    private array $stamps = [];
    private Message $message;

    public function __construct(Message $message, array $stamps = [])
    {
        $this->message = $message;

        foreach ($stamps as $stamp) {
            $this->addStamp($stamp);
        }
    }

    public static function wrap(Message $message, array $stamps = []): self
    {
        return new self($message, $stamps);
    }

    public static function clone(Envelope $envelope): self
    {
        return new self($envelope->message(), $envelope->all());
    }

    public function with(array $stamps): self
    {
        $cloned = clone $this;
        foreach ($stamps as $stamp) {
            $cloned->addStamp($stamp);
        }

        return $cloned;
    }

    public function withOut(array $stampTypes): self
    {
        $cloned = clone $this;
        foreach ($stampTypes as $stampType) {
            unset($cloned->stamps[$stampType]);
        }

        return $cloned;
    }

    public function last(string $stampType): ?Stamp
    {
        return isset($this->stamps[$stampType]) ? end($this->stamps[$stampType]) : null;
    }

    /**
     * @return array|Stamp[]
     */
    public function all(string $stampType = null): array
    {
        if ($stampType !== null) {
            return $this->stamps[$stampType] ?? [];
        }

        $stamps = [];
        foreach ($this->stamps as $stampType => $stampsByType) {
            foreach ($stampsByType as $stamp) {
                $stamps[] = $stamp;
            }
        }

        return $stamps;
    }

    public function message(): Message
    {
        return $this->message;
    }

    private function addStamp(Stamp $stamp)
    {
        $this->stamps[\get_class($stamp)][] = $stamp;
    }
}
