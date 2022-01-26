<?php

namespace App\Http\Controllers\Admin;

use App\Retencion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RetencionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Retencion::all();

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
            'importe' => ['required', 'numeric'],
        ]);

        $ret = Retencion::create($data);

        if (request()->wantsJson())
            return ['retencion'=>$ret, 'message' => 'EL registro ha sido creado'];


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Retencion $retencione )
    {
        if (request()->wantsJson())
            return [
                'retencion' =>$retencione
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Retencion $retencione)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'importe' => ['required', 'numeric'],
        ]);

        $retencione->update($data);

        if (request()->wantsJson())
            return ['retencion'=>$retencione, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retencion $retencione)
    {
        $retencione->delete();


        if (request()->wantsJson()){
            return response()->json(Retencion::all());
        }
    }
}
