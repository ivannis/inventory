<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Model;

use Hyperf\DbConnection\Model\Model;

abstract class HyperfModel extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this
            ->setTimestamps(true)
            ->setIncrementing(false)
            ->setKeyType('string')
        ;
    }

    public function setTimestamps(bool $timestamps): self
    {
        $this->timestamps = $timestamps;

        return $this;
    }

    public function setFillable(array $fillable): self
    {
        $this->fillable = $fillable;

        return $this;
    }

    public function setCasts(array $casts): self
    {
        $this->casts = $casts;

        return $this;
    }
}
