<?php

namespace App;

// use App\Albacab;
use App\Vencimiento;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Albacab extends Model
{

    use HasRoles;

    protected $dates =['fecha_alb','fecha_fac'];

    protected $appends = ['alb_ser'];

    protected $fillable = [
        'empresa_id','ejercicio','albaran', 'serie', 'fecha_alb', 'cliente_id', 'eje_fac', 'factura', 'fecha_fac',
        'fpago_id', 'vencimiento_id','iban', 'notificado', 'notas','username',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);

        static::deleting(function($albacab){

            $albacab->albalins->each->delete();
            //$post->delete();
        });
    }

    public function getFechaAlbAttribute($value)
    {

        return Carbon::parse($this->attributes['fecha_alb'])->format('Y-m-d');

    }

    public function cliente()
    {
    	return ($this->belongsTo(Cliente::class));
    }

    public function empresa()
    {
    	return ($this->belongsTo(Empresa::class));
    }

    public function fpago()
    {
    	return $this->belongsTo(Fpago::class);
    }

    public function vencimiento()
    {
    	return $this->belongsTo(Vencimiento::class);
    }


    public function albalins()
    {
        return $this->hasMany(Albalin::class);
    }

    public function getAlbSerAttribute(){


        return $this->serie.'-'.str_pad($this->albaran, 4, "0", STR_PAD_LEFT);

    }


    public function scopeRemesables($query, $fecha)
    {

        return $query->with(['cliente'])->where('fecha_fac', '=', $fecha)
                     ->where('fpago_id', '=', 3);

    }

    public function scopeAlbaranesCliente($query, $cliente_id)
    {

        return $query->with(['cliente','albalins'])
                     ->where('cliente_id', '=', $cliente_id)
                     ->orderBy('fecha_alb', 'desc');



    }


	// public static function remesarFacturas($fecha){

    //     return Albacab::Remesables($fecha)->get();

	// }

}
