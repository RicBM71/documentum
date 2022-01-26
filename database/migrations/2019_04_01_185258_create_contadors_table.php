<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contadors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->Integer('ejercicio')->default(0);
            $table->Integer('albaran')->default(0);
            $table->String('seriealb', 3)->nullable();
            $table->Integer('factura')->default(0);
            $table->String('seriefac', 3)->nullable();
            $table->Integer('abono')->default(0);
            $table->String('serieabo', 3)->nullable();
            $table->String('username')->nullable();
            $table->timestamps();
            $table->unique(['empresa_id', 'ejercicio']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contadors');
    }
}
