<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();

            $table->decimal('price', 15, 2);
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);

            $table->string('thumbnail')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(['category_id', 'status']);
            $table->index(['is_featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};