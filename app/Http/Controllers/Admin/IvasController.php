<?php

namespace App\Http\Controllers\Admin;

use App\Iva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IvasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Iva::all();

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

        $ret = Iva::create($data);

        if (request()->wantsJson())
            return ['iva'=>$ret, 'message' => 'EL registro ha sido creado'];


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Iva $iva )
    {
        if (request()->wantsJson())
            return [
                'iva' =>$iva
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Iva $iva)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'importe' => ['required', 'numeric'],
        ]);

        $iva->update($data);

        if (request()->wantsJson())
            return ['iva'=>$iva, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Iva $iva)
    {
        $iva->delete();


        if (request()->wantsJson()){
            return response()->json(Iva::all());
        }
    }
}
