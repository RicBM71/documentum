<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->String('razon', 50);
            $table->String('nombre', 50);
            $table->String('direccion',50)->nullable()->default(null);
            $table->String('cpostal',20)->nullable()->default(null);
            $table->String('poblacion', 50)->nullable()->default(null);
            $table->String('provincia', 30)->nullable()->default(null);
            $table->String('telefono1',20)->nullable()->default(null);
            $table->String('telefono2',20)->nullable()->default(null);
            $table->String('tfmovil',20)->nullable()->default(null);
            $table->String('email',50)->nullable()->default(null);
            $table->String('contacto',50)->nullable()->default(null);
            $table->String('cif',20)->nullable()->default(null);
            $table->timestamp('fechabaja')->nullable()->default(null);
            $table->String('web',50)->nullable()->default(null);
            $table->unsignedInteger('carpeta_id')->nullable()->default(null);
            $table->String('patron',50)->nullable()->default(null);
            $table->String('notas1')->nullable()->default(null);
            $table->String('efact',50)->nullable()->default(null);
            $table->String('eusr',50)->nullable()->default(null);
            $table->String('epass',50)->nullable()->default(null);
            $table->unsignedInteger('fpago_id')->nullable()->default(null);
            $table->boolean('factura')->default(true);
            $table->String('iban',24)->nullable()->default(null);
            $table->String('bic',11)->nullable()->default(null);
            $table->String('ref19',20)->nullable()->default(null);
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
        Schema::dropIfExists('clientes');
    }
}
