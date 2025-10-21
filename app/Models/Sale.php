<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'g_number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'total_price',
        'discount_percent',
        'is_supply',
        'is_realization',
        'promo_code_discount',
        'warehouse_name',
        'country_name',
        'oblast_okrug_name',
        'region_name',
        'income_id',
        'sale_id',
        'odid',
        'spp',
        'for_pay',
        'finished_price',
        'price_with_disc',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_storno',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'datetime',
        'barcode' => 'integer',
        'total_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'promo_code_discount' => 'decimal:2',
        'income_id' => 'integer',
        'odid' => 'integer',
        'spp' => 'decimal:2',
        'for_pay' => 'decimal:2',
        'finished_price' => 'decimal:2',
        'price_with_disc' => 'decimal:2',
        'nm_id' => 'integer',
        'is_storno' => 'boolean',
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
     * Scope для фильтрации по складу
     */
    public function scopeForWarehouse($query, $warehouseName)
    {
        return $query->where('warehouse_name', $warehouseName);
    }

    /**
     * Scope для фильтрации по региону
     */
    public function scopeForRegion($query, $regionName)
    {
        return $query->where('region_name', $regionName);
    }
}