<?php

use App\Albacab;
use Illuminate\Database\Seeder;

class AlbaranesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Albacab::truncate();

 		for ($i=1; $i <= 10; $i++) {

            $alb = new Albacab;
            $alb->empresa_id = 1;
            $alb->cliente_id = $i;
            $alb->fpago_id = 1;
            $alb->vencimiento_id = 1;
            $alb->serie="A";
            $alb->albaran=$i;
            $alb->fecha_alb = date('Y-m-d');
            $alb->ejercicio = date('Y');

	        $alb->save();
	    }
    }
}
