<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->float('price');
            $table->float('discountPercentage');
            $table->float('rating');
            $table->float('stock');
            $table->string('tags');
            $table->string('brand');
            $table->string('sku');
            $table->float('weight');
            $table->float('width');
            $table->float('height');
            $table->float('depth');
            $table->string('warrantyInformation');
            $table->string('shippingInformation');
            $table->string('availabilityStatus');
            $table->string('returnPolicy');
            $table->integer('minimumOrderQuantity');
            $table->string('barcode');
            $table->string('qrCode');
            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
