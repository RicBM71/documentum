<?php

namespace App\Http\Controllers\Mto;

use App\Cliente;
use App\Remesa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRemesa;

class RemesasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if (request()->session()->has('filtro_trans')){
            $filtro = request()->session()->get('filtro_trans');
            $remesas = Remesa::with(['cliente'])
                            ->where($filtro)
                            ->orderBy('fecha','desc')
                            ->get();
        }else{
            $remesas = Remesa::with(['cliente'])
                //->whereYear('fecha',date('Y'))
                ->orderBy('fecha','desc')
                ->get();
        }

        if (request()->wantsJson())
            return [
                'remesas'=> $remesas,
                'clientes' => Cliente::selClientes()->iban()->get(),
            ];

    }

    public function filtrar(Request $request)
    {

        $concepto = $request->input('concepto');
        $fecha_d = $request->input('fecha_d').'-01';
        $fecha_h = $request->input('fecha_h').'-'.date('t',strtotime($request->input('fecha_h')));

        $cliente_id = $request->input('cliente_id');

        if ($concepto > ""){
            $data[] = ['concepto', 'like' , '%'.$concepto.'%'];
        }
        else{
            $data[] = [
                'fecha', '>=', $fecha_d,
            ];
            $data[] = [
                'fecha', '<=', $fecha_h,
            ];
        }
        if ($cliente_id <> '')
            $data[] = [
                'cliente_id', '=', $cliente_id,
            ];

        session(['filtro_trans' => $data]);

        if (request()->wantsJson())
            return [
                'remesas'=> Remesa::with(['cliente'])->where($data)
                    ->orderBy('fecha','desc')
                    ->get()
            ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->wantsJson()){
            return [
                'clientes'  =>  Cliente::selClientes()->iban()->get(),
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRemesa $request)
    {

        $data = $request->validated();

        $cliente = Cliente::find($request->input('cliente_id'));

        if ($data['tipo']=='A'){
            $data['categoria'] = null;
        }else{
            $data['ref19'] = null;
        }

        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['iban_abono'] = $cliente->iban;
        $data['bic_abono'] = $cliente->bic;

        $data['username'] = $request->user()->username;

        $reg = Remesa::create($data);

        if (request()->wantsJson())
            return ['remesa'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Remesa $remesa)
    {
        if (request()->wantsJson())
            return [
                'remesa' => $remesa,
                'clientes'=> Cliente::selClientes()->iban()->get(),
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRemesa $request, Remesa $remesa)
    {
        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $remesa->update($data);

        if (request()->wantsJson())
            return ['remesa'=>$remesa, 'message' => 'La remesa ha sido modificada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Remesa $remesa)
    {
        $remesa->delete();
    }


}
