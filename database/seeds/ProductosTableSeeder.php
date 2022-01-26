<?php

use App\Producto;
use Illuminate\Database\Seeder;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Producto::truncate();

        for ($i=1; $i <= 10  ; $i++) {

            $producto = new Producto;

            $producto->nombre = "Producto ".$i;
            $producto->empresa_id=1;
            $producto->username = "ricardo.bm";
            $producto->retencion_id = 1;
            $producto->iva_id = 1;
            $producto->importe = 0;

            $producto->save();

        }
    }
}
