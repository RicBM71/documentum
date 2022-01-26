<?php

namespace App\Http\Controllers\Admin;

use App\Contador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContadores;

class ContadorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson())
            return Contador::all();
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
    public function store(StoreContadores $request, Contador $contador)
    {

        $data = $request->validated();

        $data['username'] = $request->user()->username;
        $data['empresa_id'] =  session()->get('empresa')->id;

        $reg = Contador::create($data);

        if (request()->wantsJson())
            return ['contador'=>$reg, 'message' => 'EL registro ha sido creado'];


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contador $contador )
    {
        if (request()->wantsJson())
            return [
                'contador' =>$contador,
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreContadores $request, Contador $contador)
    {

        $data = $request->validated();

        $data['username'] = $request->user()->username;
        $data['empresa_id'] =  session()->get('empresa')->id;

        $contador->update($data);

        if (request()->wantsJson())
            return ['contador'=>$contador, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contador $contador)
    {
        $contador->delete();


        if (request()->wantsJson()){
            return response()->json(Contador::all());
        }
    }
}
