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
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->string('material')->nullable();
            $table->json('sizes')->nullable();
            $table->json('features')->nullable();
            $table->integer('stock')->default(0);
            $table->json('color')->nullable();
            $table->json('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
