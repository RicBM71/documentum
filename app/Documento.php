<?php

namespace App;

use App\Scopes\EmpresaScope;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasRoles;

    protected $dates =['fecha'];

    protected $fillable = [
        'empresa_id','archivo_id','carpeta_id','fecha', 'concepto', 'cerrado','confidencial','username',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);

        static::deleting(function($documento){

            // si borramos el documento, primero relación con extracto si la hay
            $documento->extractos()->detach();

            // y borramos relación en filedocs (adjuntos) y borra también lo ficheros físicos de storage.
            // la caña, vaya!
            $documento->filedocs->each->delete();

        });
    }

    public function archivo()
    {
    	return $this->belongsTo(Archivo::class);
    }

    public function carpeta()
    {
    	return $this->belongsTo(Carpeta::class);
    }

    public function extractos()
    {
    	return $this->belongsToMany(Extracto::class);
    }

    public function filedocs(){
        return $this->hasMany(Filedoc::class);
    }

    public function scopeOrdinarios($query){

        if (!auth()->user()->hasRole('Admin'))
            return $query->where('confidencial', '=', false);

    }
}
