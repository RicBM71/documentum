<?php

use App\Categoria;
use App\Etiqueta;
use App\Post;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;


class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//Storage::disk->('public')->deleteDirectory('posts');
    	Storage::deleteDirectory('public/posts');
    	Post::truncate();
    	Categoria::truncate();
    	Etiqueta::truncate();

 		$faker = Faker::create();
	    for ($i=1; $i <= 10; $i++) { 
	    	$categoria = new Categoria;
	    	$categoria->nombre = "Categoria ".$i;
	    	$categoria->save();
	    }

 		for ($i=0; $i < 10; $i++) { 

 			$post = new Post;
	        $post->titulo = $faker->sentence();
	        $post->extracto = $faker->sentence();
	        $post->cuerpo = $faker->paragraph();
	        $post->fecha_publi = $faker->dateTime()->format('d/m/Y');
	        $post->categoria_id = $faker->randomDigitNotNull();
	        if ($i <= 5) $post->user_id = 1;
	        else $post->user_id = 2;
	        $post->save();
	    }


	    for ($i=1; $i <= 10; $i++) { 
	    	$etiqueta = new Etiqueta;
	    	$etiqueta->nombre = "Etiqueta ".$i;
	    	$etiqueta->save();
	    }


			// DB::table('posts')->insert([
		 //            'title' => $faker->sentence(),
		 //            'excerpt' =>  $faker->sentence(),
		 //            'body' =>  $faker->paragraph(),
		 //            'published_at' => $faker->dateTime(),
		 //            'categoria_id' => randomDigit()
		 //        ]);
		

    }
}
