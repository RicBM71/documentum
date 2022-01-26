<?php

namespace App;

use App\Scopes\EmpresaScope;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasRoles;

    protected $fillable = [
        'empresa_id', 'nombre', 'retencion_id', 'iva_id', 'importe','baja', 'username',
    ];

    /**
     *
     * Añadimos global scope para filtrado por empresa.
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function retencion()
    {
    	return $this->belongsTo(Retencion::class);
    }

    public function iva()
    {
    	return $this->belongsTo(Iva::class);
    }

    public static function selProductos(){

        return Producto::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }

}
