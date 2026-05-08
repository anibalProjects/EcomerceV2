<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carrito_productos', function (Blueprint $table) {
            $table->dropForeign('carrito_productos_mueble_id_foreign');
        });
    }

    public function down(): void
    {
        Schema::table('carrito_productos', function (Blueprint $table) {
            $table->foreign('mueble_id')
                  ->references('id')
                  ->on('muebles')
                  ->onDelete('set null');
        });
    }
};
