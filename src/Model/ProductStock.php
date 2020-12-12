<?php

declare(strict_types=1);

namespace Stock\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * ProductStock class.
 *
 * @property int $quantity
 * @property float $valuation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductStock extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_stock';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'quantity',
        'valuation',
        'product_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quantity' => 'integer',
        'valuation' => 'float',
        'product_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'product_stock_id', 'id');
    }
}
