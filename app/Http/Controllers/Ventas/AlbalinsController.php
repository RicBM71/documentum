<?php

namespace App\Http\Controllers\Ventas;

use App\Iva;
use App\Albalin;
use App\Producto;
use App\Retencion;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAlbalin;
use App\Http\Controllers\Controller;

class AlbalinsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', new Albalin);

        if (request()->wantsJson())
        return [
            'productos'=>  Producto::selProductos(),
            'iva'  =>  Iva::selIvas(),
            'irpf'  =>  Retencion::selRetenciones(),
    ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbalin $request)
    {

        $this->authorize('create', new Albalin);
        //$this->authorize('create', new Albacab);

        $data = $request->validated();

        $data['username'] = $request->user()->username;


        $reg = Albalin::create($data);

        if (request()->wantsJson())
            return [
                'lineas' => Albalin::Albacab($reg->albacab_id)->get(),
                'totales' => Albalin::totalAlbaran($reg->albacab_id)
            ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('create', new Albalin);

         if (request()->wantsJson())
             return [
                 'lineas' => Albalin::Albacab($id)->get(),
                 'totales' => Albalin::totalAlbaran($id)
             ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Albalin $albalin)
    {
        $this->authorize('create', new Albalin);

        if (request()->wantsJson())
           return [
            'albalin'   =>  $albalin,
            'productos' =>  Producto::selProductos(),
            'iva'       =>  Iva::selIvas(),
            'irpf'      =>  Retencion::selRetenciones(),

        ];


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAlbalin $request, Albalin $albalin)
    {
        $this->authorize('create', new Albalin);

        $data = $request->validated();

        $data['username'] = $request->user()->username;


        $albalin->update($data);

        if (request()->wantsJson())
            return [
                'lineas'    => Albalin::Albacab($albalin->albacab_id)->get(),
                'totales'   => Albalin::totalAlbaran($albalin->albacab_id)
            ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Albalin $albalin)
    {

        $this->authorize('create', new Albalin);

        if (!$request->user()->hasRole('Root')){
            return response("No autorizado", 403);
        }

        $id = $albalin->albacab_id;

        $albalin->delete();

        if (request()->wantsJson()){
            return [
                'lineas' => Albalin::Albacab($id)->get(),
                'totales' => Albalin::totalAlbaran($id)
            ];
        }

    }
}
