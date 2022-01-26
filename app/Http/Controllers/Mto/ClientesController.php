<?php

namespace App\Http\Controllers\Mto;

use App\Fpago;
use App\Albacab;
use App\Carpeta;
use App\Cliente;
use App\Documento;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientes;
use Spatie\Permission\Models\Permission;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $baja = "A";
        if (request()->session()->has('filtro_cli')){
            $baja = request()->session()->get('filtro_cli');
        }

        if (request()->wantsJson())
            return $this->seleccionar($baja);

    }


/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtrar(Request $request)
    {

        $baja = $request->input('fbaja');

        session(['filtro_cli' => $baja]);

        if (request()->wantsJson()){
            return $this->seleccionar($baja);
        }

    }

    /**
     *  @param array $data // condiciones where genéricas
     *  @param array $doc  // condiciones para documentos
     */
    private function seleccionar($baja="A"){

        if ($baja == "T")
            return Cliente::all();
        elseif ($baja == "A")
            return Cliente::whereNull('fechabaja')->get();
        else
            return Cliente::where('fechabaja','>','')->get();

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->wantsJson())
            return [
                'carpetas'=> Carpeta::selCarpetas(),
                'fpagos'=> Fpago::selFPagos(),
            ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientes $request)
    {
        $data = $request->validated();

        //\Log::info('enviando mensaje...'.session()->get('empresa'));
        $data['empresa_id'] =  session()->get('empresa')->id;

        $data['username'] = $request->user()->username;


        $reg = Cliente::create($data);

        if (request()->wantsJson())
            return ['cliente'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    /**
     * Muestra albaranes del cliente
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function albaranes($id)
    {

       // $o = new Albacab;

        //return $o->AlbaranesCliente($id)->get();
        //return Albacab::AlbaranesCliente($id)->get();

        if (request()->wantsJson())
            return [
                'albaranes'=> Albacab::AlbaranesCliente($id)->get()
            ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {

        if (request()->wantsJson())
            return [
                'cliente' =>$cliente,
                'documentos'=> Documento::where('carpeta_id','=',$cliente->carpeta_id)->orderBy('fecha','desc')->take(50)->get(),
                'carpetas'=> Carpeta::selCarpetas(),
                'fpagos'=> Fpago::selFPagos(),
            ];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        if (request()->wantsJson())
            return [
                'cliente' =>$cliente,
                'documentos'=> Documento::where('carpeta_id','=',$cliente->carpeta_id)->orderBy('fecha','desc')->take(50)->get(),
                'carpetas'=> Carpeta::selCarpetas(),
                'fpagos'=> Fpago::selFPagos(),
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientes $request, Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $cliente->update($data);

        if (request()->wantsJson())
            return [
                'cliente'=>$cliente,
                'documentos'=> Documento::where('carpeta_id','=',$cliente->carpeta_id)->orderBy('fecha','desc')->take(50)->get(),
                'message' => 'EL cliente ha sido modficado'
                ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {

        $this->authorize('delete', $cliente);

        $cliente->delete();


        if (request()->wantsJson()){
            return response()->json(Cliente::all());
        }
    }
}
