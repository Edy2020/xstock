<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('descuento_total')->default(0);
            $table->bigInteger('total')->default(0);
            $table->string('metodo_pago')->default('efectivo');
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();

            $table->string('producto_nombre');
            $table->integer('cantidad');
            $table->bigInteger('precio_unitario');
            $table->integer('descuento_porcentaje')->default(0);
            $table->bigInteger('subtotal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
        Schema::dropIfExists('ventas');
    }
};
