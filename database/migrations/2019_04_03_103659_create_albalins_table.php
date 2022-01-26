<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbalinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albalins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('albacab_id');
            $table->unsignedInteger('producto_id');
            $table->String('nombre', 50)->nullable();
            $table->Integer('unidades')->default(0);
            $table->decimal('impuni', 10, 2)->default(0);
            $table->decimal('poriva', 5, 2)->default(0);
            $table->decimal('porirpf', 5, 2)->default(0);
            $table->decimal('dto', 5, 2)->default(0);
            $table->decimal('importe', 10, 2)->default(0);
            $table->String('username', 20)->nullable();
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
        Schema::dropIfExists('albalins');
    }
}
