<?php

namespace App;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Filedoc extends Model
{
    // protected $fillable = [
    //     'documento_id','empresa_id','url','username',
    // ];


    //deshabiltamos protección de asignación masiva al asignar los campos
    //uno por uno en create
    protected $guarded=[];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaScope);

        static::deleting(function($fichero){
    		// esto no va
                //Storage::disk('local')->delete($fichero->url);

            //Storage::disk('local')->delete('documentum/EmiokS0jXnZVu5OYyQLVJsJnR9uzGrfLhHoq6y1c.pdf');

            //\Log::info('pasa por deleting'.$fichero->url);

            $ficheroPath = str_replace('/storage', '', $fichero->url);

            //\Log::info('pasa por deleting'.$ficheroPath);

       		Storage::delete($ficheroPath);
    	});
    }

    public function scopeFilesDocumento($query, $documento_id)
    {

        return $query->where('documento_id', '=', $documento_id);



    }

}
