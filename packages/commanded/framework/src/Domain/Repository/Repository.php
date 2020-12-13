<?php

declare(strict_types=1);

namespace Commanded\Domain\Repository;

use Commanded\Core\ValueObject\Identity\Id;
use Commanded\Domain\Aggregate\AggregateRoot;

interface Repository
{
    public function create(AggregateRoot $aggregateRoot);

    public function save(AggregateRoot $aggregateRoot);

    public function delete(Id $id);

    public function load(Id $id): AggregateRoot;
}
