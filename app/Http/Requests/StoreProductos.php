<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductos extends FormRequest
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
        $data = [

            'nombre'        => ['required', 'string'],
            'retencion_id'  => ['required','numeric'],
            'iva_id'        => ['required','numeric'],
            'importe'       => ['required','numeric'],
            'username'      => ['nullable','string'],
        ];


        return $data;
    }
}
