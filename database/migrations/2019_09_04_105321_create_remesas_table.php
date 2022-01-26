<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remesas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->String('tipo',1)->default('A'); // Adeudo - Transferencia
            $table->String('categoria',4)->nullable()->default(null); // Para transferencia->SALA: Salario OTHR: Otros SUPP: Pagos a proveedores
            $table->unsignedInteger('cliente_id');
            $table->date('fecha');
            $table->String('iban_cargo',24)->nullable()->default(null);
            $table->String('bic_cargo',11)->nullable()->default(null);
            $table->String('iban_abono',24)->nullable()->default(null);
            $table->String('bic_abono',11)->nullable()->default(null);
            $table->string('ref19',20)->nullable();
            $table->decimal('importe', 10, 2)->default(0);
            $table->String('concepto', 100);
            $table->boolean('enviada')->default(false);
            $table->String('username',20)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remesas');
    }
}
