<?php

namespace App;

use App\Retencion;
use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    protected $table = 'retenciones';

    protected $fillable = [
        'nombre', 'importe',
    ];

    public static function selRetenciones(){

        return Retencion::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

}
