<?php

use App\Cliente;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

 		for ($i=0; $i < 20; $i++) {

            $cliente = new Cliente;
            $cliente->empresa_id = 1;
	        $cliente->nombre = $faker->name;
            $cliente->razon = $faker->name;
            if ($i > 12)
                $cliente->empresa_id = 2;

	        $cliente->save();
	    }

    }
}
