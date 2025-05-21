<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationSetOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variation_set_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variation_set_id')->constrained('product_variation_sets')->onDelete('cascade');
            $table->foreignId('variation_option_id')->constrained('variation_options')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['product_variation_set_id', 'variation_option_id'], 'variation_set_option_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variation_set_options');
    }
}
