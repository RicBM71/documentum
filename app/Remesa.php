<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Remesa extends Model
{
    protected $fillable = [
        'empresa_id', 'tipo', 'categoria','cliente_id','fecha', 'concepto', 'iban_cargo', 'bic_cargo', 'iban_abono', 'bic_abono','importe',
        'ref19','concepto','enviada','username'
    ];

    protected $dates =['fecha'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function cliente()
    {
    	return ($this->belongsTo(Cliente::class));
    }

    public function scopeRemesar($query,$fecha){

        return $query->where('fecha', '=', $fecha)
                     ->where('enviada', '=', false);

    }

    public function scopeRecibos($query,$fecha){

        return $query->where('fecha', '=', $fecha)
                     ->where('tipo', '=', 'A')
                     ->where('enviada', '=', false);

    }
}
