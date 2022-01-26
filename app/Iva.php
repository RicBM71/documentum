<?php

namespace App;

use App\Iva;
use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $fillable = [
        'nombre', 'importe',
    ];

    public static function selIvas(){

        return Iva::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

}
