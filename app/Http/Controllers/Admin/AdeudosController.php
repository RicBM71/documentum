<?php

namespace App\Http\Controllers\Admin;

use App\Cuenta;
use App\Remesa;
use App\Albacab;
use App\Albalin;
use App\Cliente;
use Illuminate\Http\Request;

use Digitick\Sepa\GroupHeader;
use App\Http\Controllers\Controller;
use Digitick\Sepa\PaymentInformation;
use Illuminate\Support\Facades\Storage;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;


class AdeudosController extends Controller
{
     /**
     * Selección previa remesa
     */
    public function index(){

        if (!auth()->user()->hasRole('Admin'))
            abort(403,'No Autorizado');

        if (request()->wantsJson())
            return ['cuentas'=> Cuenta::selCuentas()];
    }

     public function remesar(Request $request){

        if (!auth()->user()->hasRole('Admin'))
            abort(403,'No Autorizado');

        $data = $request->validate([
            'cuenta_id'=>'required|integer',
            'fecha'=>'required|date',
            'fecha_cob'=>'required|date'
        ]);

        $alb =  Albacab::Remesables($data['fecha'])->get();

        if ($alb->count() == 0)
            abort(404,'No hay facturas para remesar');

        $remesa = $this->generarAdeudos($alb,$data['cuenta_id'],$data['fecha_cob']);

        if (request()->wantsJson())
            return [
                'importe'   => $remesa['importe'],
                'adeudos'   => $remesa['adeudos']
            ];

    }

    private function generarAdeudos($data, $cuenta_id, $fecha_cob){

        $cuenta = Cuenta::find($cuenta_id);

        $imp_total_remesa = $adeudos = 0;
        foreach ($data as $row){

            $total = Albalin::totalLineasByAlb($row->id);

            $cliente = Cliente::find($row->cliente_id);

            $remesa['tipo'] = 'A';
            $remesa['fecha'] = $fecha_cob;
            $remesa['empresa_id'] =  session()->get('empresa')->id;
            $remesa['cliente_id'] = $row->cliente_id;

            $remesa['iban_cargo'] = $cuenta->iban;
            $remesa['bic_cargo'] = $cuenta->bic;

            $remesa['iban_abono'] = $cliente->iban;
            $remesa['bic_abono'] = $cliente->bic;
            $remesa['ref19'] = $cliente->ref19;

            $remesa['concepto']='Factura '.$row->factura;
            $remesa['enviada'] = false;
            $remesa['importe'] = $total['total'];

            $remesa['username'] = session()->get('username');

            $reg = Remesa::create($remesa);

            $imp_total_remesa += $total['total'];
            $adeudos++;
        }

        return [
            'importe' => round($imp_total_remesa, 2),
            'adeudos' => $adeudos
        ];

    }






}
