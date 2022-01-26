<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $fillable = [
        'empresa_id','cliente_id','fecha', 'concepto', 'iban_cargo', 'bic_cargo', 'iban_abono', 'bic_abono','importe',
        'concepto','enviada','username'
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
}
