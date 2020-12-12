<?php

declare(strict_types=1);

namespace Stock\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * StockItem class.
 *
 * @property int $id
 * @property int $quantity
 * @property float $unit_price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class StockItem extends Model
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'stock_item';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'quantity',
        'unit_price',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'float',
        'created_at' => 'datetime:Y-m-d',
    ];

    public function calculatePrice(int $quantity = null): float
    {
        return $this->unit_price * ($quantity ?? $this->quantity);
    }

    public function onHand(): int
    {
        return $this->quantity;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity === 0;
    }

    public function hasStockSufficient(int $quantity): bool
    {
        return $this->quantity >= $quantity;
    }

    public function decrementStock(int $quantity)
    {
        $this->quantity -= $quantity;
    }
}
