<?php

namespace App\Http\Controllers\Admin;

use App\Archivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchivosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Archivo::all();

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
            'nombre' => ['required', 'string', 'max:255'],
            'color' => ['string','required'],
            'path' => ['string','required'],
            'docuzip' => ['boolean']
        ]);

        $reg = Archivo::create($data);

        if (request()->wantsJson())
            return ['archivo'=>$reg, 'message' => 'EL registro ha sido creado'];


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Archivo $archivo )
    {
        if (request()->wantsJson())
            return [
                'archivo' =>$archivo
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Archivo $archivo)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'color' => ['string','required'],
            'path' => ['string','required'],
            'docuzip' => ['boolean']
        ]);


        $archivo->update($data);

        if (request()->wantsJson())
            return ['archivo'=>$archivo, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archivo $archivo)
    {
        $archivo->delete();


        if (request()->wantsJson()){
            return response()->json(Archivo::all());
        }
    }
}
