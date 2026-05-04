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
        Schema::create('muebles', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->string('nombre');
            $table->text('descripcion');
            $table->float('precio')->default(0);
            $table->string('color')->default('navy');
            $table->integer('stock')->default(0);
            $table->boolean('novedad')->default(false);
            $table->text('materiales');
            $table->text('dimensiones');
            $table->string('imagen_principal')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muebles');
    }
};
