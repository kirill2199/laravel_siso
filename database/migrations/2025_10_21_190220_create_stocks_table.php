<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->dateTime('last_change_date')->index();
            $table->string('supplier_article', 100);
            $table->string('tech_size', 100);
            $table->bigInteger('barcode');
            $table->integer('quantity')->default(0);
            $table->boolean('is_supply')->default(false);
            $table->boolean('is_realization')->default(false);
            $table->integer('quantity_full')->default(0);
            $table->string('warehouse_name', 255);
            $table->integer('in_way_to_client')->default(0);
            $table->integer('in_way_from_client')->default(0);
            $table->bigInteger('nm_id');
            $table->string('subject', 255);
            $table->string('category', 255);
            $table->string('brand', 255);
            $table->bigInteger('sc_code');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->default(0);
            
            // Индексы для оптимизации запросов
            $table->index(['date', 'warehouse_name']);
            $table->index('nm_id');
            $table->index('barcode');
            $table->index('supplier_article');
            $table->index('brand');
            $table->index('category');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};