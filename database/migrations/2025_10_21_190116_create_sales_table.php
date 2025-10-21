<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('g_number', 100)->index();
            $table->date('date')->index();
            $table->dateTime('last_change_date')->index();
            $table->string('supplier_article', 100);
            $table->string('tech_size', 100);
            $table->bigInteger('barcode');
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->boolean('is_supply')->default(false);
            $table->boolean('is_realization')->default(false);
            $table->decimal('promo_code_discount', 10, 2)->nullable();
            $table->string('warehouse_name', 255);
            $table->string('country_name', 100);
            $table->string('oblast_okrug_name', 100);
            $table->string('region_name', 100);
            $table->bigInteger('income_id')->default(0);
            $table->string('sale_id', 50)->index();
            $table->bigInteger('odid')->nullable();
            $table->decimal('spp', 5, 2)->default(0);
            $table->decimal('for_pay', 12, 2);
            $table->decimal('finished_price', 12, 2);
            $table->decimal('price_with_disc', 12, 2);
            $table->bigInteger('nm_id')->index();
            $table->string('subject', 255);
            $table->string('category', 255);
            $table->string('brand', 255);
            $table->boolean('is_storno')->nullable();
            
            // Индексы для оптимизации запросов
            $table->index(['date', 'warehouse_name']);
            $table->index(['date', 'brand']);
            $table->index(['date', 'category']);
            $table->index('supplier_article');
            $table->index('barcode');
            $table->index('region_name');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};