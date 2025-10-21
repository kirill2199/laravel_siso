<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'nm_id',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'datetime',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'quantity_full' => 'integer',
        'in_way_to_client' => 'integer',
        'in_way_from_client' => 'integer',
        'nm_id' => 'integer',
        'sc_code' => 'integer',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    /**
     * Scope для фильтрации по дате
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope для фильтрации по складу
     */
    public function scopeForWarehouse($query, $warehouseName)
    {
        return $query->where('warehouse_name', $warehouseName);
    }

    /**
     * Scope для товаров в наличии
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}