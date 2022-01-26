<?php

namespace App;

use App\Archivo;
use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $fillable = [
        'nombre', 'color', 'path', 'docuzip'
    ];

    protected static function boot()
    {
        parent::boot();

    }

    public function carpetas()
    {
        return $this->hasMany(Carpeta::class);
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
    public static function selArchivos(){

        return Archivo::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

    public function setNombreAttribute($s)
    {
        $this->attributes['nombre'] = strtoupper($s);

    }

    public function scopeDocuZip($query)
    {

        return $query->where('docuzip', true);
    }


}
