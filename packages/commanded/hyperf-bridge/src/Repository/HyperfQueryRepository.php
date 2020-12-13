<?php

declare(strict_types=1);

namespace Commanded\HyperfBridge\Repository;

use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\Core\ValueObject\Identity\Id;
use Commanded\Domain\Repository\QueryRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Str;
use Stock\Infrastructure\Hyperf\Model\HyperfStock;

class HyperfQueryRepository implements QueryRepository
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(Id $id, array $state)
    {
        $this->persist($id, $state);
    }

    public function save(Id $id, array $state)
    {
        $this->persist($id, $state, false);
    }

    public function delete(Id $id)
    {
        $this->query()->findOrFail($id->toNative())->delete();
    }

    public function findOne(Id $id): ?array
    {
        return $this->modelToState(
            $this->query()->find($id->toNative())
        );
    }

    public function findOneOrFail(Id $id): array
    {
        return $this->modelToState(
            $this->query()->findOrFail($id->toNative())
        );
    }

    public function findOneBy(array $criteria, array $orderBy = []): ?array
    {
        return $this->modelToState(
            $this->addOrderBy($this->query()->where($criteria), $orderBy)->first()
        );
    }

    public function findOneByOrFail(array $criteria, array $orderBy = []): array
    {
        return $this->modelToState(
            $this->addOrderBy($this->query()->where($criteria), $orderBy)->firstOrFail()
        );
    }

    public function findBy(array $criteria = [], array $orderBy = []): iterable
    {
        return $this->modelsToState(
            $this->addOrderBy($this->query()->where($criteria), $orderBy)->get()
        );
    }

    public function findAll(array $orderBy = []): iterable
    {
        return $this->modelsToState(
            $this->addOrderBy($this->query(), $orderBy)->get()
        );
    }

    protected function query(): Builder
    {
        return $this->model->newQuery(true);
    }

    protected function addOrderBy(Builder $query, array $orderBy = []): Builder
    {
        foreach ($orderBy as $sortField => $sortOrder) {
            $query = $query->orderBy($sortField, $sortOrder);
        }

        return $query;
    }

    private function persist(Id $id, array $state, bool $isNew = true): void
    {
        $model = $this->stateToModel($id, $state, $isNew);
        $model->push();
    }

    private function stateToModel(Id $id, array $state, bool $isNew = true): Model
    {
        $attributes = array_merge($state, [
            'id' => (string) $id
        ]);

        $model = $this->model->newInstance();
        if ($isNew == false) {
            $model = $model->newQuery()->findOrFail($id->toNative());
        }

        return $model->fill($attributes);
    }

    private function modelToState(?Model $model): ?array
    {
        return $model ? $this->extractAttributes($model) : null;
    }

    private function modelsToState(Collection $models): Collection
    {
        return $models->map(fn(Model $model) => $model->attributesToArray());
    }

    private function extractAttributes(Model $model): array
    {
        $relations = [];
        foreach ($model->relationsToArray() as $key => $value) {
            $relations[Str::camel($key)] = $value;
        }

        return array_merge($model->attributesToArray(), $relations);
    }
}
