<?php

namespace App\Http\Controllers\Admin;

use App\Cuenta;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCuenta;
use App\Http\Controllers\Controller;

class CuentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson())
            return Cuenta::all();
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
    public function store(StoreCuenta $request)
    {
        $data = $request->validated();

        //\Log::info('enviando mensaje...'.session()->get('empresa'));
        $data['empresa_id'] =  session()->get('empresa')->id;

        $data['username'] = $request->user()->username;

        $reg = Cuenta::create($data);

        if (request()->wantsJson())
            return ['cuenta'=>$reg, 'message' => 'El registro ha sido creado'];
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cuenta $cuenta)
    {
        if (request()->wantsJson())
            return [
                'cuenta' =>$cuenta
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\StoreCuenta  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCuenta $request, Cuenta $cuenta)
    {

        // \Log::info('enviando mensaje...');

        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $cuenta->update($data);

        if (request()->wantsJson())
            return ['cuenta'=>$cuenta, 'message' => 'La cuenta ha sido modficada'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuenta $cuenta)
    {
        //$this->authorize('delete', $cuenta);

        $cuenta->delete();
        
        if (request()->wantsJson()){
            return response()->json(Cuenta::all());
        }
    }
}
