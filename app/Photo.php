<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    //deshabiltamos protección de asignación masiva al asignar los campos
    //uno por uno en create
    protected $guarded=[];

    protected static function boot(){

    	parent::boot();

    	static::deleting(function($foto){
    		// esto no va
    		//	Storage::disk('public')->delete($foto->url);
    		$fotoPath = str_replace('storage', 'public', $foto->url);
       		Storage::delete($fotoPath);
    	});
    }
}
