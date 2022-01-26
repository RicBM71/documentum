<?php

namespace App\Http\Controllers\Mto;

use App\Iva;
use App\Producto;
use App\Retencion;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductos;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Producto::with(['iva','retencion'])->get();


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
        $this->authorize('update', new Producto);

        if (request()->wantsJson())
            return [
                'ivas'=> Iva::selIvas(),
                'retenciones'=> Retencion::selRetenciones(),
            ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductos $request)
    {
        $this->authorize('update', new Producto);

        $data = $request->validated();

      // \Log::info('enviando mensaje...'.session()->get('empresa'));
        $data['empresa_id'] =  session()->get('empresa')->id;

        $data['username'] = $request->user()->username;


        $reg = Producto::create($data);

        if (request()->wantsJson())
            return ['producto'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

 /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

         if (request()->wantsJson())
             return [
                 'producto' => Producto::with(['retencion','iva'])->find($id)
             ];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);

        if (request()->wantsJson())
            return [
                'producto' =>$producto,
                'ivas'=> Iva::selIvas(),
                'retenciones'=> Retencion::selRetenciones(),
            ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductos $request, Producto $producto)
    {
        $this->authorize('update', $producto);

        $data = $request->validated();

        $data['username'] = $request->user()->username;

        $producto->update($data);

        if (request()->wantsJson())
            return ['producto'=>$producto, 'message' => 'EL producto ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {

        $this->authorize('delete', $producto);

        $producto->delete();


        if (request()->wantsJson()){
            return response()->json(Producto::with(['iva','retencion'])->get());
        }
    }
}
