<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 255); // Increase the length to 255
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock')->default(0);
            $table->integer('discount')->default(0); // Use integer for discount percentage
            $table->string('brand')->default('SHEIN'); // Add brand column with default value
            $table->foreignId('main_category_id')->nullable()->constrained('main_categories')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('sub_sub_category_id')->nullable()->constrained('sub_sub_categories')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
