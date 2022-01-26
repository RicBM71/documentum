<?php

namespace App\Http\Controllers\Admin;

use App\Carpeta;
use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresas;

class EmpresasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Empresa::all();

        if (request()->wantsJson())
            return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $data = $request->validate([
            'nombre' => ['required','string'],
            'razon' => ['required', 'string'],
            'titulo' => ['required','string'],
        ]);


        $data['username'] = $request->user()->username;

        $reg = Empresa::create($data);

        $request->user()->empresas()->attach($reg->id);

        if (request()->wantsJson())
            return ['empresa'=>$reg, 'message' => 'EL registro ha sido creado'];


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa )
    {
        if (request()->wantsJson())
            return [
                'empresa' =>$empresa,
                'carpetas'=>Carpeta::withoutGlobalScope(EmpresaScope::class)
                    ->select('id AS value', 'nombre AS text')
                        ->where('empresa_id','=',$empresa->id)
                        ->where('activa','=',true)
                        ->orderBy('nombre')->get()
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEmpresas $request, Empresa $empresa)
    {

        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $empresa->update($data);

        if (request()->wantsJson())
            return ['empresa'=>$empresa, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();


        if (request()->wantsJson()){
            return response()->json(Empresa::all());
        }
    }
}
