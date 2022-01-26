<?php

namespace App\Http\Controllers\Admin;

use App\Fpago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FpagosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson())
            return Fpago::all();
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

        $ret = Fpago::create($data);

        if (request()->wantsJson())
            return ['fpago'=>$ret, 'message' => 'EL registro ha sido creado'];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Fpago $fpago)
    {
        if ($fpago->id <= 3)
            abort(422, 'No es posible modificar el tipo');

        if (request()->wantsJson())
        return [
            'fpago' =>$fpago
        ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fpago $fpago)
    {
        $data = $request->validate([
            'id' => 'required',
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        if ($fpago->id <= 3)
            abort(422, 'No es posible modificar el tipo');

        $fpago->update($data);

        if (request()->wantsJson())
            return ['fpago'=>$fpago, 'message' => 'EL registro ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fpago $fpago)
    {

        if ($fpago->id <= 3)
        abort(422, 'No es posible modificar el tipo');

        $fpago->delete();

        if (request()->wantsJson()){
            return response()->json(Fpago::all());
        }
    }
}
