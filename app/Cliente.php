<?php

namespace App;

use App\Scopes\EmpresaScope;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    use HasRoles;

    protected $fillable = [
        'empresa_id','nombre', 'razon', 'cif', 'poblacion', 'direccion', 'cpostal','provincia', 'telefono1','telefono2','tfmovil',
        'fechaalta','fechabaja','web','carpeta_id','patron','notas1','efact','eusr','epass','contacto','email','patron',
        'fpago_id','factura','iban','bic','ref19','username'
    ];


    protected $dates =['fechalta','fechabaja'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);
    }

    public function setNombreAttribute($nombre)
    {
        $this->attributes['nombre'] = strtoupper($nombre);

    }

    public function setCifAttribute($cif)
    {
        $this->attributes['cif'] = strtoupper($cif);

    }
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);

    }

    public function setWebAttribute($web)
    {
        $this->attributes['web'] = strtolower($web);

    }

    public function setIbanAttribute($iban)
    {
        $this->attributes['iban'] = strtoupper($iban);

    }

    public function setBicAttribute($bic)
    {
        $this->attributes['bic'] = strtoupper($bic);

    }

    public function albacabs()
    {

         return $this->hasMany(Albacabs::class);

    }

    public function scopeFacturables($query)
    {

        return $query->where('factura', true);
    }

    public function scopeConPatron($query){
        return $query->where('patron', '>', '');
    }


    public static function selClientes(){

        return Cliente::select('id AS value', 'nombre AS text', 'ref19 AS ref19');

    }

    public function scopeIban($query)    {

        return $query->where('iban', '>', '');

    }

}

