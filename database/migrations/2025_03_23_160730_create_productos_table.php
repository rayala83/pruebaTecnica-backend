<?php

// archivo: database/migrations/2025_03_24_123456_create_productos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();  // ID auto incremental
            $table->string('name');  // Nombre del producto
            $table->integer('quantity');  // Cantidad
            $table->decimal('weight', 8, 2);  // Peso con 2 decimales
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    /**
     * Reversa las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');  // Elimina la tabla 'productos'
    }
}
