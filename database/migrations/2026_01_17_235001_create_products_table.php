<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('code', 100)->unique();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('brand', 150)->nullable();
            $table->string('frame_material', 150)->nullable();
            $table->string('lens_material', 150)->nullable();
            $table->string('shape', 100)->nullable();
            $table->string('rim_type', 100)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('frame_color', 255)->nullable();
            $table->string('lens_color', 255)->nullable();
            $table->text('colors')->nullable();
            $table->integer('lens_width')->nullable();
            $table->integer('bridge_width')->nullable();
            $table->integer('temple_length')->nullable();
            $table->integer('frame_width')->nullable();
            $table->integer('price');
            $table->integer('discount_price')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('image', 255)->nullable();
            $table->string('image_detail_1', 255)->nullable();
            $table->string('image_detail_2', 255)->nullable();
            $table->string('image_detail_3', 255)->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_featured')->default(0);
            $table->timestamps();
            $table->index('category_id', 'products_category_id_foreign');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
