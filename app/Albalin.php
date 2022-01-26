<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Albalin extends Model
{

    use HasRoles;

    protected $fillable = [
        'albacab_id','producto_id','nombre', 'unidades', 'impuni', 'poriva', 'porirpf', 'dto',
        'importe', 'username',
    ];

    public function producto()
    {
    	return $this->belongsTo(Producto::class);
    }
    // public function retencion()
    // {
    // 	return $this->belongsTo(Retencion::class);
    // }
    // public function iva()
    // {
    // 	return $this->belongsTo(Iva::class);
    // }

    public function scopeAlbacab($query, $albacab_id)
    {
        // esto permite relacionar retencion e iva en el objeto producto, aquí no hace falta pero lo usaremos.
        //$query->with(['producto','producto.retencion','producto.iva'])
        $query->with(['producto'])
                    ->where('albacab_id', '=', $albacab_id );

    }

    public static function totalAlbaran($id){
        return DB::table('albalins')
                ->select(DB::raw('SUM(unidades) AS unidades, ROUND(SUM(importe*(poriva/100)), 2) AS impiva, ROUND(SUM(importe*(porirpf/100)), 2) AS impirpf, SUM(importe) AS importe'))
                ->where('albacab_id', $id)
                ->first();
    }

    public static function totalLineasByAlb($id){

        $q = DB::table('albalins')
            ->select(DB::raw(DB::getTablePrefix().'albalins.poriva,'.
                             DB::getTablePrefix().'albalins.porirpf,'.
                             DB::getTablePrefix().'SUM(importe) AS importe'))
            ->where('albacab_id', $id)
            ->groupBy('poriva','porirpf')
            ->get();

        $data = [
            'importe'       => 0,
            'total'         => 0,
            'base_iva'      => 0,
            'irpf'          => 0,
            'total_irpf'    => 0,
            'iva'           => 0,
            'desglose_iva'  => [],
        ];

        $desglose_iva = array();
        foreach ($q as $row){

            $base_iva  = $row->importe;
            $cuota_iva = round($row->importe * $row->poriva / 100, 2);
            $cuota_irpf = round($row->importe * $row->porirpf / 100, 2);

            $desglose_iva[] = [
                'por_iva'   => $row->poriva,
                'base_iva'  => $base_iva,
                'cuota_iva' => $cuota_iva,
            ];

            if ($row->poriva > 0)
                $data['base_iva']+= $base_iva;

            $data['iva']+= $cuota_iva;
            if ($row->porirpf <> 0)
                $data['irpf']+= $row->porirpf;

            $data['importe']+= $row->importe;
            $data['total_irpf']+= $cuota_irpf;

        }

        $data['desglose_iva'] = $desglose_iva;
        $data['total'] = round($data['importe'] + $data['iva'] - $data['total_irpf'], 2);


        return $data;

    }


    // public static function totalLineasByAlb($id){

    //     $q = DB::table('albalins')
	// 	    ->select(DB::raw('poriva, porirpf, ROUND(SUM((importe * poriva / 100)), 2) AS iva, ROUND(SUM((importe * porirpf /100)),2) AS irpf, ROUND(SUM(importe),2) AS base, ROUND(SUM(importe + (importe * poriva /100) - (importe * porirpf /100)),2) AS importe'))
	// 	    ->where('albacab_id',$id)
    //         ->groupby('poriva','porirpf')
    //         ->get();

    //     foreach ($q as $row){

    //     }

    //     return $q;
	// 	return array('importe'=> round($q->importe,2),
	// 			'iva'=> $q->iva,
	// 			'irpf'=> $q->irpf,
	// 			'poriva'=> $q->poriva,
	// 			'porirpf'=> $q->porirpf,
	// 			'base'=> round($q->base,2));

	// }


}
