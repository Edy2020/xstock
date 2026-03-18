<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Cambia precio a entero (CLP no tiene centavos)
            $table->bigInteger('precio')->default(0)->change();
            // Agrega relación con proveedor (opcional)
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->default(0)->change();
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn('proveedor_id');
        });
    }
};
