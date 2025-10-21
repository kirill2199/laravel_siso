<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'datetime',
        'date_close' => 'date',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
        'income_id' => 'integer',
        'nm_id' => 'integer',
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
}