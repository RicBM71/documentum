<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbalin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'albacab_id'    =>'numeric',
            'producto_id'   =>'numeric',
            'nombre'    =>'nullable|string',
            'unidades'  =>'numeric',
            'impuni'    =>'numeric',
            'poriva'    =>'numeric',
            'porirpf'   =>'numeric',
            'dto'       =>'numeric',
            'importe'   =>'numeric'
        ];
    }
}
