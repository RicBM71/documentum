<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{

	protected $guarded = [];

    public function posts()
    {
    	return $this->belongsToMany(Post::class);
    }
}
