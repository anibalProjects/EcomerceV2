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
        Schema::create('carrito_productos', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('mueble_id')->nullable();
            $table->unsignedBigInteger('carrito_id')->nullable();
            $table->Integer('cantidad');
            $table->timestamps();
            $table->foreign('mueble_id')->references('id')->on('muebles')->onDelete('set null');
            $table->foreign('carrito_id')->references('id')->on('carrito')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrito_productos');
    }
};
