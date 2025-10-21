<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('income_id')->unique();
            $table->string('number', 100)->default('');
            $table->date('date')->index();
            $table->dateTime('last_change_date')->index();
            $table->string('supplier_article', 100);
            $table->string('tech_size', 100);
            $table->bigInteger('barcode');
            $table->integer('quantity')->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->date('date_close');
            $table->string('warehouse_name', 255);
            $table->bigInteger('nm_id')->index();
            
            // Индексы для оптимизации запросов
            $table->index(['date', 'warehouse_name']);
            $table->index('supplier_article');
            $table->index('barcode');
            $table->index('income_id');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};