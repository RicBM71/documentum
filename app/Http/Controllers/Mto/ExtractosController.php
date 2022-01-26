<?php

namespace App\Http\Controllers\Mto;

use App\Cuenta;
use App\Carpeta;
use App\Cliente;
use App\Empresa;
use App\Filedoc;
use App\Extracto;
use App\Documento;
use Illuminate\Http\Request;
use App\Exports\ExtractosExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExtractosController extends Controller
{

    public $movicuen;
    public $patrones;
    public $carpeta_id_patron;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return request()->session()->has('filtro_extrac');
        if (request()->wantsJson()){
            if (request()->session()->has('filtro_extrac')){

                $filtro = request()->session()->get('filtro_extrac');
                $docu = request()->session()->get('filtro_extrac_docu');

                return $this->seleccionar($filtro, $docu);
            }
            else
                return $this->seleccionar();
        }

    }

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtrar(Request $request)
    {

        $fecha_d = $request->input('fecha_d').'-01';
        $fecha_h = $request->input('fecha_h').'-'.date('t',strtotime($request->input('fecha_h')));
        $dh = $request->input('dh');
        $docu = $request->input('docu');
        $validado = $request->input('validado');
        $concepto = $request->input('concepto');

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
        if ($dh <> 'T')
            $data[] = [
                'dh', '=', $dh,
            ];
        if ($validado <> 'T')
            $data[] = [
                'validado', '=', $validado,
            ];

        session(['filtro_extrac' => $data]);
        session(['filtro_extrac_docu' => $docu]);

        if (request()->wantsJson()){
            return $this->seleccionar($data,$docu);
        }

    }

    /**
     *  @param array $data // condiciones where genéricas
     *  @param array $doc  // condiciones para documentos
     */
    private function seleccionar($data=false, $docu=false){

        if ($data == false)
            return Extracto::select('extractos.*')
                        ->with(['documentos','documentos.archivo','documentos.carpeta'])
                        ->whereYear('fecha',date('Y'))
                        ->orderBy('fecha','desc')
                        ->get();
        else
            return Extracto::select('extractos.*')
                ->with(['documentos','documentos.archivo','documentos.carpeta'])
                ->conDocumentos($docu)
                ->where($data)
                ->orderBy('fecha')
                ->get();

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "show".$id;
        // return Documento::with(['extractos'])
        //     ->where('carpeta_id',6)
        //     ->toSql();

       // \Log::info('show-ext');
        //return session()->get('empresa')->id;
    }

    public function banco(){

        $carpeta = Carpeta::with('archivo')->find(session()->get('empresa')->carn43_id);

        // Creamos el documento para guardar el archivo del banco

        $data=[
            'empresa_id' => session()->get('empresa_id'),
            'archivo_id' => $carpeta->archivo->id,
            'carpeta_id' => $carpeta->id,
            'fecha' => date('Y-m-d'),
            'concepto' => 'Norma 43',
            'username' => session()->get('username'),

        ];

        $doc = Documento::create($data);

        $this->validate(request(),[
            'files' => 'required'
        ]);

        $documento = Documento::with(['archivo','carpeta'])->find($doc->id);
        // almacenamos el fichero físico y creamos el link en filedocs

        $ejercicio = date('Y',strtotime($documento->fecha));

        //$path = 'documentum/'.session()->get('empresa')->path_archivo.'/'.$documento->archivo->path.'/'.$documento->carpeta->path;
        $path = 'documentum/'.session()->get('empresa')->path_archivo.'/'.$ejercicio.'/'.$documento->archivo->path.'/'.$documento->carpeta->path;

        $files = request()->file('files')->store($path,'local');

    	$filesUrl = Storage::url($files);

    	// 	//insert en la tabla photos
    	Filedoc::create([
             'url'	=> $filesUrl,
             'empresa_id' => session()->get('empresa')->id,
             'documento_id' => $doc->id,
             'username' => request()->user()->username
        ]);

            // vamos a procesar la remesa

        return $this->importar($doc->id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Extracto $extracto)
    {

        abort(404);

        // if ($documento->confidencial && !auth()->user()->hasRole('Admin')){
        //     abort(404);
        // }

        // if (request()->wantsJson())
        //     return [
        //         'documento' => $documento,
        //         'archivos'=> Archivo::selArchivos(),
        //         'carpetas'=> Carpeta::selCarpetasArchivo($documento->archivo_id),
        //         'extractos'=> $documento->extractos,
        //         'files' => Filedoc::FilesDocumento($documento->id)->get()
        //     ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extracto $extracto)
    {

        $data['concepto'] = $request->input('concepto');
        $data['nota'] =  $request->input('nota');
        $data['username'] = $request->user()->username;

        $extracto->update($data);

        if (request()->wantsJson())
            return ['extracto'=>$extracto, 'message' => 'EL documento ha sido modficado'];
    }

    public function liberar(Request $request, Extracto $extracto)
    {

        $data['validado'] =  $request->input('validado');
        $data['username'] = $request->user()->username;

        $extracto->update($data);

        if (request()->wantsJson())
            return ['extracto'=>$extracto, 'message' => 'EL documento ha sido liberado'];
    }

    public function importar($id){


        $documento = Documento::where('id', $id)->with('filedocs')->first();

        // cargamos patrones de búsqueda
        $patro_cli = Cliente::conpatron()->select('id', 'patron', 'carpeta_id')->get();

        $this->patrones = $patro_cli->merge(Carpeta::conpatron()->select('id AS carpeta_id', 'patron')->get());

        $filename = $documento->filedocs->first()->url;
        $filename = str_replace('/storage', 'app', $filename);
        $filename = storage_path($filename);

		// Control de linea
		$registros = 0;
		$fp = @fopen($filename,"r");
		// si no existe el fichero, salimos
		if (!$fp){
			return response(401,'No se ha podido leer el fichero');
		}

        $this->carpeta_id_patron = 0;
        $concepto = null;
		$registros = 0;
		while ($linea = fgets($fp,1024)){
			$reg    = substr($linea, 0, 2);

			switch ($reg) {
                case '11': // cabecera de cuenta

                    $this->movicuen['cuenta_id'] = $this->leerCuenta($linea);


                    $this->movicuen['username'] = session()->get('username');
                    $this->movicuen['concepto'] = null;

					$nuevo = false;
					break;
				case '22':
					if ($nuevo){

                        $this->movicuen['concepto'] = ($concepto);

                        $reg = Extracto::create($this->movicuen);

                        if ($this->carpeta_id_patron > 0){

                            $mi_carpeta = Carpeta::find($this->carpeta_id_patron);

                            $data=[
                                'empresa_id' => session()->get('empresa_id'),
                                'archivo_id' => $mi_carpeta->archivo_id,
                                'carpeta_id' => $this->carpeta_id_patron,
                                'fecha' =>  $this->movicuen['fecha'],
                                'cerrado' => false,
                                'concepto' => $this->movicuen['concepto'],
                                'username' => session()->get('username'),

                            ];

                            $doc = Documento::create($data);

                            $doc->extractos()->attach($reg->id);
                        }

                        $this->movicuen['concepto'] = $concepto = null;
                        $this->carpeta_id_patron = 0;

                        $registros++;

						$nuevo = false;
					}

					$nuevo = $this->registroPrincipal($linea);
					break;
				case '23':
					$concepto .= $this->conceptos($linea);
					break;
				case '24': // equivalencia divisa. No hago nada
					break;
                case '33':	// fin de cuenta.

                    //$this->movicuen['concepto'] = utf8_encode($concepto);
                    $this->movicuen['concepto'] = $concepto;
                    $reg = Extracto::create($this->movicuen);
					$registros++;

					$tot_d = (int) substr($linea, 20,5);
					$tot_h = (int) substr($linea, 39,5);
					break;
				case '88':	// fin de fichero
					//$regfile = substr($linea, 20,6);
					break;
				default:
					;
					break;
			}
		}
		fclose($fp);

		$regfile = $tot_d + $tot_h;

        $mensaje = "Actualizados ".$registros.' de '.$regfile. '-> Debe: '.$tot_d.' Haber: ' .$tot_h;

        if (request()->wantsJson())
            return $mensaje;

	}

	private function leerCuenta($linea){

        $iban = substr($linea,10,10);

        $cuenta = Cuenta::where('iban', 'like', '%'.$iban.'%')->firstOrFail();

        return $cuenta->id;

	}

	private function registroPrincipal($linea){

        //$this->movicuen['fecha'] = substr($linea, 14, 2).'/'.substr($linea, 12, 2).'/20'.substr($linea,10,2);

        $this->movicuen['fecha'] = '20'.substr($linea,10,2).'-'.substr($linea, 12, 2).'-'.substr($linea, 14, 2);

        substr($linea, 27,1) == '1'? $this->movicuen['dh'] = "D" : $this->movicuen['dh'] = "H";

        $this->movicuen['importe'] = (float) substr($linea, 28,14) / 100;

        $this->movicuen['empresa_id'] = session()->get('empresa_id');

		// if ($this->movicuen->dh == "H")
		// 	$this->concepto_aux = substr($linea, 64,16);
		// else
		// 	$this->concepto_aux = null;

		return true;
	}

	private function conceptos($linea){

        $concepto = (trim(substr($linea, 4, 38)).trim(substr($linea, 42, 38)));

        //$concepto = strtoupper(utf8_encode($concepto));


		// buscamos la partida correspondiente.

		foreach ($this->patrones as $item) {
			if (strstr($concepto,$item->patron) != false){

                $this->carpeta_id_patron = $item->id;

				break;
			}
        }

        return $concepto;

    }

    public function export($ejercicio)
    {
        //$ejercicio = explode("-",$request->input('fecha'));

        // $this->validate($ejercicio,[
        //     'files' => 'required|integer'
        // ]);

        return Excel::download(new ExtractosExport($ejercicio), 'extracto.xlsx');
    }


}
