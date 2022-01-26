<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Extracto extends Model
{

    protected $dates =['fecha'];

    protected $fillable = [
        'empresa_id','cuenta_id', 'concepto', 'nota', 'dh', 'fecha','importe','validado','username'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function documentos(){

        return $this->belongsToMany(Documento::class);

    }

    public function scopeConDocumentos($query, $tipo){

        if ($tipo == "S")
            return $query->join('documento_extracto', 'extractos.id', '=', 'documento_extracto.extracto_id');
        elseif ($tipo == "N")
            return $query->whereNotIn('extractos.id', function($q){
                $q->select('extracto_id')->from('documento_extracto');
            });
        else return $query;

    }

}
