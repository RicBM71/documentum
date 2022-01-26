<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{

    protected $fillable = [
        'empresa_id','nombre', 'iban', 'bic', 'sufijo_adeudo','sufijo_transf','activa', 'username'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public static function selCuentas(){

        return Cuenta::select('id AS value', 'nombre AS text')
            ->where('activa',1)
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public function scopeIbanLike($query, $iban){

        return $query->where('iban', 'like', '%'.$iban.'%');
    }

    public function scopeActiva($query){
        return $query->where('activa', true);
    }
}
