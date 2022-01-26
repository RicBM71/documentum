<?php

namespace App\Http\Controllers\Mto;

use App\Vencimiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VencimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson())
            return Vencimiento::all();
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
        ]);

        $ret = Vencimiento::create($data);

        if (request()->wantsJson())
            return ['vencimiento'=>$ret, 'message' => 'EL registro ha sido creado'];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Vencimiento $vencimiento)
    {
        if (request()->wantsJson())
        return [
            'vencimiento' =>$vencimiento
        ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vencimiento $vencimiento)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $vencimiento->update($data);

        if (request()->wantsJson())
            return ['vencimiento'=>$vencimiento, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vencimiento $vencimiento)
    {
        $vencimiento->delete();


        if (request()->wantsJson()){
            return response()->json(Vencimiento::all());
        }
    }
}
