<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('product_variation_set_options', 'product_variation_set_variation_option');
    }

    public function down(): void
    {
        Schema::rename('product_variation_set_variation_option', 'product_variation_set_options');
    }
};
