<?php

namespace App;

use App\Fpago;
use Illuminate\Database\Eloquent\Model;

class Fpago extends Model
{
    protected $fillable = [
        'nombre'
    ];

    /**
     *
     * @return Array formateado para select Vuetify
     *
     */
    public static function selFPagos(){

        return Fpago::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

        // return DB::table('fpagos')->select('id AS field', 'nombre AS text')
        //             ->orderBy('nombre', 'asc')
        //             ->get();
    }
}
