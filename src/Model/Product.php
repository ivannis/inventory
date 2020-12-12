<?php

declare(strict_types=1);

namespace Stock\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * Product class.
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    /**
     * @var string
     */
    protected $table = 'product';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
