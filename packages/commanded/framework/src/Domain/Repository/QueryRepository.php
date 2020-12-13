<?php

declare(strict_types=1);

namespace Commanded\Domain\Repository;

use Commanded\Core\ValueObject\Identity\Id;

interface QueryRepository
{
    public function create(Id $id, array $state);

    public function save(Id $id, array $state);

    public function delete(Id $id);

    public function findOne(Id $id): ?array;

    public function findOneOrFail(Id $id): array;

    public function findOneBy(array $criteria, array $orderBy = []): ?array;

    public function findOneByOrFail(array $criteria, array $orderBy = []): array;

    public function findBy(array $criteria = [], array $orderBy = []): iterable;

    public function findAll(array $orderBy = []): iterable;
}
