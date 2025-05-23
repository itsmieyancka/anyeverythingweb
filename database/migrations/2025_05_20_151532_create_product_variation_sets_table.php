<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationSetsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variation_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable();
            $table->json('variation_option_ids');
            $table->integer('stock')->default(0);
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variation_sets');
    }
}
