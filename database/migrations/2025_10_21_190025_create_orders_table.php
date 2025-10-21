<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->dateTime('last_change_date')->index();
            $table->string('supplier_article', 100);
            $table->string('tech_size', 100);
            $table->bigInteger('barcode');
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->string('warehouse_name', 255);
            $table->string('oblast', 255);
            $table->bigInteger('income_id')->default(0);
            $table->string('odid', 50)->index();
            $table->bigInteger('nm_id')->index();
            $table->string('subject', 255);
            $table->string('category', 255);
            $table->string('brand', 255);
            $table->boolean('is_cancel')->default(false);
            $table->dateTime('cancel_dt')->nullable();
            $table->string('g_number', 100)->index();
            $table->string('sticker', 255)->nullable();
            $table->string('srid', 100)->nullable();
            
            // Дополнительные поля для заказов
            $table->integer('quantity')->default(1);
            $table->decimal('finished_price', 12, 2);
            $table->decimal('price_with_disc', 12, 2);
            $table->boolean('is_supply')->default(false);
            $table->boolean('is_realization')->default(false);
            $table->decimal('spp', 5, 2)->default(0);
            $table->string('order_type', 50)->default('Клиентский');
            $table->string('status', 50)->default('awaiting_registration');
            
            // Индексы для оптимизации запросов
            $table->index(['date', 'warehouse_name']);
            $table->index(['date', 'brand']);
            $table->index(['date', 'category']);
            $table->index('supplier_article');
            $table->index('barcode');
            $table->index('oblast');
            $table->index('is_cancel');
            $table->index('status');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};