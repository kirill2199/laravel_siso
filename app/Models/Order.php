<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'total_price',
        'discount_percent',
        'warehouse_name',
        'oblast',
        'income_id',
        'odid',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
        'g_number',
        'sticker',
        'srid',
        'quantity',
        'finished_price',
        'price_with_disc',
        'is_supply',
        'is_realization',
        'spp',
        'order_type',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'datetime',
        'cancel_dt' => 'datetime',
        'barcode' => 'integer',
        'total_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'income_id' => 'integer',
        'nm_id' => 'integer',
        'is_cancel' => 'boolean',
        'quantity' => 'integer',
        'finished_price' => 'decimal:2',
        'price_with_disc' => 'decimal:2',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'spp' => 'decimal:2',
    ];

    /**
     * Scope для фильтрации по дате
     */
    public function scopeDateRange($query, $dateFrom, $dateTo = null)
    {
        $dateTo = $dateTo ?: $dateFrom;
        
        return $query->whereBetween('date', [
            $dateFrom,
            $dateTo
        ]);
    }

    /**
     * Scope для активных заказов (не отмененных)
     */
    public function scopeActive($query)
    {
        return $query->where('is_cancel', false);
    }

    /**
     * Scope для фильтрации по складу
     */
    public function scopeForWarehouse($query, $warehouseName)
    {
        return $query->where('warehouse_name', $warehouseName);
    }

    /**
     * Scope для фильтрации по статусу
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}