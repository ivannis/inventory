<?php

declare(strict_types=1);

namespace Commanded\HyperfBridge\Repository;

use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\Core\ValueObject\Identity\Id;
use Commanded\Domain\Aggregate\AggregateRoot;
use Commanded\Domain\Repository\Repository;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Utils\Str;

class HyperfRepository implements Repository
{
    private Model $model;
    private string $aggregateClass;
    private EventRecorder $eventRecorder;

    public function __construct(Model $model, string $aggregateClass, EventRecorder $eventRecorder)
    {
        $this->model = $model;
        $this->aggregateClass = $aggregateClass;
        $this->eventRecorder = $eventRecorder;
    }

    public function load(Id $id): AggregateRoot
    {
        $model = $this->query()->findOrFail($id->toNative());

        return $this->modelToAggregate($model);
    }

    public function create(AggregateRoot $aggregateRoot)
    {
        $this->persist($aggregateRoot);
    }

    public function save(AggregateRoot $aggregateRoot)
    {
        $this->persist($aggregateRoot, false);
    }

    public function delete(Id $id)
    {
        $this->query()->findOrFail($id->toNative())->delete();
    }

    protected function query(): Builder
    {
        return $this->model->newQuery(true);
    }

    private function persist(AggregateRoot $aggregate, bool $isNew = true): void
    {
        $model = $this->aggregateToModel($aggregate, $isNew);
        $model->push();

        $this->eventRecorder->recordEvents(...$aggregate->recordedEvents()->toArray());
        $aggregate->clearRecordedEvents();
    }

    private function aggregateToModel(AggregateRoot $aggregate, bool $isNew = true): Model
    {
        $aggregateState = $aggregate->toSnapshot();
        $attributes = array_merge($aggregateState['state'], [
            'id' => $aggregateState['id']
        ]);

        $model = $this->model->newInstance();
        if ($isNew == false) {
            $model = $model->newQuery()->findOrFail($aggregate->id()->toNative());
        }

        return $model->fill($attributes);
    }

    private function modelToAggregate(Model $model): AggregateRoot
    {
        $state = $this->extractAttributes($model);
        $id = $state[$model->getKeyName()];

        unset(
            $state[$model->getKeyName()]
        );

        return call_user_func([$this->aggregateClass, 'fromSnapshot'], [
            'id' => $id,
            'version' => 0,
            'state' => $state,
        ]);
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
