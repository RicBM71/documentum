<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }


    protected $fillable = [
        'empresa_id', 'archivo_id', 'nombre','path', 'color', 'patron', 'activa'
    ];

    // una carpeta tiene un archivo y solo uno
    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }


    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

  /**
     *
     * @return Array formateado para select Vuetify
     *
     */
    public static function selCarpetas(){

        return Carpeta::select('id AS value', 'nombre AS text')
            ->activas()
            ->orderBy('nombre', 'asc')
            ->get();

    }

    public static function selCarpetasArchivo($archivo_id){

        return Carpeta::select('id AS value', 'nombre AS text')
            ->activas()
            ->conarchivo($archivo_id)
            ->orderBy('nombre', 'asc')
            ->get();

    }


    public function scopeConArchivo($query, $archivo_id){

        return $query->where('archivo_id', '=', $archivo_id);

    }

    public function scopeActivas($query){

        return $query->where('activa', '=', true);

    }

    public function scopeMiEmpresa($query, $empresa_id){

        return $query->where('empresa_id', '=', $empresa_id);

    }

    public function scopeConPatron($query){
        return $query->where('patron', '>', '');
    }

}
