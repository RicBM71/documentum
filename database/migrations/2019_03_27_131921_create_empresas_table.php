<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50);
            $table->string('razon', 50);
            $table->string('cif', 20)->nullable()->default(null);
            $table->string('poblacion', 50)->nullable()->default(null);
            $table->string('direccion', 50)->nullable()->default(null);
            $table->string('cpostal', 20)->nullable()->default(null);
            $table->string('provincia', 50)->nullable()->default(null);
            $table->string('telefono1', 20)->nullable()->default(null);
            $table->string('telefono2', 20)->nullable()->default(null);
            $table->string('contacto', 30)->nullable()->default(null);
            $table->string('email', 50)->nullable()->default(null);
            $table->string('web', 50)->nullable()->default(null);
            $table->string('txtpie1', 150)->nullable()->default(null);
            $table->string('txtpie2', 150)->nullable()->default(null);
            $table->string('flags', 20)->nullable()->default(null);
            $table->string('sigla', 10)->nullable()->default(null);
            $table->string('path_archivo')->nullable()->default(null);
            $table->string('titulo', 20);
            $table->string('logo',20)->nullable()->default(null);
            $table->string('certificado',20)->nullable()->default(null);
            $table->string('passwd_cer')->nullable()->default(null);
            $table->unsignedInteger('carext_id')->nullable()->default(null);
            $table->unsignedInteger('carn43_id')->nullable()->default(null);
            $table->string('username',30)->nullable()->default(null);
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
        Schema::dropIfExists('empresas');
    }
}
