<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 8, 2)->after('status');
            $table->decimal('shipping', 8, 2)->after('subtotal')->default(0);
        });
    }


    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
