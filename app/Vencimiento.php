<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vencimiento extends Model
{
    protected $fillable = [
        'nombre'
    ];

    /**
     *
     *  Duvuelve vencimientos formateados para cargar en select Vuetify
     *
     */
    public static function selVencimientos(){

        return Vencimiento::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }
}
