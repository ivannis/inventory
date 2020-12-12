<?php

declare(strict_types=1);

namespace Stock\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * Movement class.
 *
 * @property int $id
 * @property string $type
 * @property int $quantity
 * @property float $unit_price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class InventoryHistory extends Model
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'inventory_history';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'quantity',
        'unit_price',
        'product_id',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'quantity' => 'integer',
        'unit_price' => 'float',
        'product_id' => 'integer',
        'created_at' => 'datetime:Y-m-d',
    ];
}
