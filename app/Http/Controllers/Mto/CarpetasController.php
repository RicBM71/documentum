<?php

namespace App\Http\Controllers\Mto;

use App\Carpeta;
use App\Archivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarpetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->authorize('view', new Carpeta);

        $data = Carpeta::with('archivo')->get();
        //$data = Carpeta::all();

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
        $this->authorize('create', new Carpeta);

        if (request()->wantsJson())
            return [
                'archivos'  =>  Archivo::selArchivos(),
            ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('create', new Carpeta);

        $data = $request->validate([
            'archivo_id' => ['required','integer'],
            'nombre' => ['required', 'string', 'max:50'],
            'path' => ['nullable','string'],
            'patron' => ['nullable','string'],
            'color' => ['nullable','string'],

        ]);

        $data['empresa_id'] =  session()->get('empresa')->id;

        $reg = Carpeta::create($data);

        if (request()->wantsJson())
            return ['carpeta'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id (id del archivo)
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (request()->wantsJson())
            return [
                'carpetas'=> Carpeta::selCarpetasArchivo($id),
            ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Carpeta $carpeta)
    {
        $this->authorize('update', $carpeta);

        if (request()->wantsJson())
            return [
                'archivos' => Archivo::selArchivos(),
                'carpeta' =>$carpeta
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carpeta $carpeta)
    {
        $this->authorize('update', $carpeta);

        $data = $request->validate([
            'archivo_id' => ['required'],
            'nombre' => ['required', 'string', 'max:50'],
            'path' => ['nullable','string'],
            'color' => ['nullable','string'],
            'patron' => ['nullable','string'],
            'activa'=> ['required','boolean']
        ]);


        $carpeta->update($data);

        if (request()->wantsJson())
            return ['carpeta'=>$carpeta, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carpeta $carpeta)
    {
        $this->authorize('delete', $carpeta);

        $carpeta->delete();

        if (request()->wantsJson()){
            return response()->json(Carpeta::with('archivo')->get());
        }
    }
}
