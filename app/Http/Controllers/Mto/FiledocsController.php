<?php

namespace App\Http\Controllers\Mto;

use App\Filedoc;
use App\Documento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FiledocsController extends Controller
{

    public function store($documento_id){

        \Log::info('store');
        $this->validate(request(),[
            'files' => 'required'
        ]);

        //return request()->file('files');

        $documento = Documento::with(['archivo','carpeta'])->find($documento_id);

        $ejercicio = date('Y',strtotime($documento->fecha));

        $path = 'documentum/'.session()->get('empresa')->path_archivo.'/'.$ejercicio.'/'.$documento->archivo->path.'/'.$documento->carpeta->path;

        $files = request()->file('files')->store($path,'local');


    	$filesUrl = Storage::url($files);

    	// 	//insert en la tabla photos
    	Filedoc::create([
             'url'	=> $filesUrl,
             'empresa_id' => session()->get('empresa')->id,
             'documento_id' => $documento_id,
             'username' => request()->user()->username
        ]);

        return [
            'files' => Filedoc::FilesDocumento($documento_id)->get()
        ];
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Filedoc $filedoc)
    {
        $filedoc->delete();

        if (request()->wantsJson()){
            return [
                'files' => Filedoc::FilesDocumento($filedoc->documento_id)->get()
            ];
        }
    }

    public function show(Filedoc $filedoc){

        $ficheroPath = str_replace('/storage', '', $filedoc->url);

        $ext = explode('.',$ficheroPath);
        $ext = array_pop($ext);

        $filename = 'docu'.date('Ymd').'.'.$ext;

        $img = ['pdf','jpg','jpeg','png','gif'];

        if (in_array($ext, $img)) // este abre el fichero en el navegador
            return response()->file(storage_path('/app/' . $ficheroPath));
        else
            return Storage::download($ficheroPath, $filename);

        //return response()->download(storage_path('/app/' . $ficheroPath,$filename));

    }




}
