<?php

declare(strict_types=1);

namespace Commanded\Domain\Aggregate;

use Commanded\Core\ValueObject\Identity\Id;

trait SnapshotTrait
{
    protected string $id;
    protected int $version = 0;
    protected array $state = [];

    public static function fromId(Id $id): self
    {
        return new static((string) $id);
    }

    public function toSnapshot(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'state' => $this->state,
        ];
    }

    public static function fromSnapshot(array $snapshot): self
    {
        return new static(
            $snapshot['id'],
            $snapshot['version'],
            $snapshot['state']
        );
    }
}
