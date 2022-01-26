<?php

namespace App;

use App\Contador;
use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{

    protected $fillable = [
        'empresa_id', 'ejercicio','albaran', 'seriealb', 'factura', 'seriefac', 'abono', 'serieabo','username',
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

    /**
     * @param integer ejercicio
     * @return App\contador $contador
     */
    public static function incrementaContador($ejercicio){

        //abort(423,'Something went wrong');

        try {

            $contador =  Contador::where('ejercicio',$ejercicio)->lockForUpdate()->firstOrFail();
            $contador->albaran++;

            $arr = [
                'albaran' => $contador->albaran,
                'username' => session()->get('username')
            ];

            Contador::where('id', $contador->id)->update($arr);

            return $contador;

          } catch (\Exception $e) {
                return $e->getMessage();
          }
    }

    /**
     * @param integer ejercicio
     * @return App\contador $contador
     */
    public static function incrementaContadorFactura($ejercicio){

        //abort(423,'Something went wrong');

        try {

            $contador =  Contador::where('ejercicio',$ejercicio)->lockForUpdate()->firstOrFail();
            $contador->factura++;

            $arr = [
                'factura' => $contador->factura,
                'username' => session()->get('username')
            ];

            Contador::where('id', $contador->id)->update($arr);



            $l = strlen($contador->factura);

            if ($l <= 3)
                return $contador->seriefac."-".str_repeat('0', 4-$l).$contador->factura;
            else
                return $contador->seriefac.'-'.$contador->factura;

          } catch (\Exception $e) {
                return $e->getMessage();
          }
    }

    /**
     * @param integer ejercicio
     * @param integer la factura para comparar si se puede o no retrasar el contador, solo si es la misma.
     * @return App\contador $contador
     */
    public static function restaContadorFactura($ejercicio, $factura){

        $obj = explode('-',$factura);

        try {

            $sincronizado = false;

            $contador =  Contador::where('ejercicio',$ejercicio)->lockForUpdate()->firstOrFail();

            if ($contador->factura == $obj[1]){
                $sincronizado = true;
                $contador->factura--;
            }

            $arr = [
                'factura' => $contador->factura,
                'username' => session()->get('username')
            ];

            Contador::where('id', $contador->id)->update($arr);

            return $sincronizado;

          } catch (\Exception $e) {
                return $e->getMessage();
          }
    }
}
