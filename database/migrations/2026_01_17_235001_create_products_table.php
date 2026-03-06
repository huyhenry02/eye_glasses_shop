<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->bigInteger('category_id')->unsigned();
            $table->string('code', 100);
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description');
            $table->string('weight', 255);
            $table->string('dimension', 255);
            $table->string('material', 255);
            $table->text('colors');
            $table->integer('price');
            $table->integer('discount_price')->nullable();
            $table->integer('stock_quantity');
            $table->string('image', 255)->nullable();
            $table->string('image_detail_1', 255)->nullable();
            $table->string('image_detail_2', 255)->nullable();
            $table->string('image_detail_3', 255)->nullable();
            $table->string('style')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_featured')->default(0);
            $table->timestamps();
            $table->index('category_id', 'products_category_id_foreign');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
