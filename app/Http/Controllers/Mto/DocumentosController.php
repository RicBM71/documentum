<?php

namespace App\Http\Controllers\Mto;

use App\Archivo;
use App\Carpeta;
use App\Filedoc;
use App\Documento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumento;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->session()->has('filtro_docu')){
            $filtro = request()->session()->get('filtro_docu');
            $documentos = Documento::ordinarios()
                            ->with(['archivo','carpeta','filedocs','extractos'])
                            ->where($filtro)
                            ->orderBy('fecha','desc')
                            ->get();
        }else{
            $documentos = Documento::ordinarios()
                ->with(['archivo','carpeta','filedocs', 'extractos'])
                ->whereYear('fecha',date('Y'))
                ->orderBy('fecha','desc')
                ->get();
        }

        if (request()->wantsJson())
            return [

                'documentos'=> $documentos,
                'archivos'  =>  Archivo::selArchivos(),
                'archi_defecto' => Archivo::docuzip()->select('id')->pluck('id')
            ];

    }

    public function filtrar(Request $request)
    {


        $concepto = $request->input('concepto');
        $fecha_d = $request->input('fecha_d').'-01';
        $fecha_h = $request->input('fecha_h').'-'.date('t',strtotime($request->input('fecha_h')));

        $archivo_id = $request->input('archivo_id');

        if ($concepto > ""){
            $data[] = ['concepto', 'like' , '%'.$concepto.'%'];
        }
        else{
            $data[] = [
                'fecha', '>=', $fecha_d,
            ];
            $data[] = [
                'fecha', '<=', $fecha_h,
            ];
        }
        if ($archivo_id <> '')
            $data[] = [
                'archivo_id', '=', $archivo_id,
            ];

        session(['filtro_docu' => $data]);

        if (request()->wantsJson())
            return [
                'documentos'=> Documento::with(['archivo','carpeta','filedocs'])->where($data)
                            ->orderBy('fecha','desc')
                            ->get()
            ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->authorize('create', new Documento);

        if (request()->wantsJson())
            return [
                'archivos'=> Archivo::selArchivos(),
                'carpetas'=> []
            ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumento $request)
    {

        $this->authorize('create', new Documento);

        $data = $request->validated();

        //\Log::info('enviando mensaje...'.session()->get('empresa'));
        $data['empresa_id'] =  session()->get('empresa')->id;

        $data['username'] = $request->user()->username;

        $reg = Documento::create($data);

        $extracto_id = $request->get('extracto_id');
        if ($extracto_id > 0)
            $reg->extractos()->sync($extracto_id);

        if (request()->wantsJson())
            return ['documento'=>$reg, 'message' => 'EL registro ha sido creado'];
    }

    public function attach(Request $request){

        $this->authorize('create', new Documento);

        $data = $request->input('documentos');

        foreach ($data as $id){

            $documento = Documento::findOrfail($id);

            $documento->extractos()->syncWithoutDetaching($request->get('extractos'));
        }

        return response('ok',200);
    }

    public function detach(Request $request, Documento $documento){

        $this->authorize('create', $documento);

        $documento->extractos()->detach($request->get('extracto_id'));
        return response('ok',200);
    }


    public function extracto(Request $request){

        $data['fecha'] = $request->get('fecha');
        $data['concepto'] = $request->get('concepto');

        $data['empresa_id'] =  session()->get('empresa')->id;
        $data['username'] = $request->user()->username;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $documento = Documento::with(['archivo','carpeta'])->findOrfail($id);

        if (request()->wantsJson())
            return [
                'documento' => $documento,
                'extractos'=> $documento->extractos,
                'files' => Filedoc::FilesDocumento($documento->id)->get()
            ];


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {


        if ($documento->confidencial && !auth()->user()->hasRole('Admin')){
            abort(404);
        }

        if (request()->wantsJson())
            return [
                'documento' => $documento,
                'archivos'=> Archivo::selArchivos(),
                'carpetas'=> Carpeta::selCarpetasArchivo($documento->archivo_id),
                'extractos'=> $documento->extractos,
                'files' => Filedoc::FilesDocumento($documento->id)->get()
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDocumento $request, Documento $documento)
    {
        $this->authorize('update', $documento);

        $data = $request->validated();

        $data['empresa_id'] =  session()->get('empresa_id');
        $data['username'] = $request->user()->username;

        $documento->update($data);

        if (request()->wantsJson())
            return ['documento'=>$documento, 'message' => 'EL documento ha sido modficado'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento);

        //$documento->extractos()->sync([]);

        $documento->delete();

        if (request()->wantsJson()){
            return [
                // 'documentos'=> Documento::with(['archivo','carpeta'])->whereYear('fecha',date('Y'))
                //             ->orderBy('fecha','desc')
                //             ->get(),
                'archivos'  =>  Archivo::selArchivos(),
            ];
        }
    }

    public function zip(Request $request){



        $periodo = explode("-", $request->input('fecha_d'));
        $archivo_id = $request->input('archivo_id');
        // $archivo_id = 4;
        // $periodo=[2019,1];


        //     $path = storage_path('invoices');

        $i = 1;

        $fecha_d = $request->input('fecha_d').'-01';
        $fecha_h = $request->input('fecha_h').'-'.date('t',strtotime($request->input('fecha_h')));

        $files = Documento::ordinarios()->with('filedocs')
        // ->whereYear('fecha',$periodo[0])
        // ->whereMonth('fecha',$periodo[1])
        ->whereIn('archivo_id',$archivo_id)
        ->whereBetween('fecha', [$fecha_d, $fecha_h])
        ->orderBy('fecha')
        ->get();


        if ($files->count() == 0){
            abort(404, 'No hay documentos');
        }

        $zip_file = storage_path('zip/filedoc.zip');

        if (file_exists(storage_path('zip'))==false)
            mkdir(storage_path('zip'), '0755');

        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $str="";

        foreach ($files as $doc){
           // dd($doc->filedocs);

            foreach ($doc->filedocs as $file){
                $ficheroPath = str_replace('/storage', 'app', $file->url);

                $filename = explode(".",$file->url);

                $zip->addFile(storage_path($ficheroPath), $i.'.'.$filename[1]);

                $str.=($i.'.'.$filename[1].': '.getFecha($doc->fecha).' -> '.$doc->concepto)."\r\n";

                $i++;
            }

        }

        $zip->addFromString('indice.txt',$str);

        $zip->close();

        return response()->download($zip_file);

    }
}
