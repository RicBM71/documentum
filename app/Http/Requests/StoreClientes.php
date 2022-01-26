<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientes extends FormRequest
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

            'nombre'        => ['required', 'string', 'max:50'],
            'razon'         => ['nullable','string', 'max:50'],
            'poblacion'     => ['nullable','string', 'max:50'],
            'direccion'     => ['nullable','string', 'max:50'],
            'cpostal'       => ['nullable','string', 'max:20'],
            'provincia'     => ['nullable','string', 'max:50'],
            'telefono1'     => ['nullable','string', 'max:20'],
            'telefono2'     => ['nullable','string', 'max:20'],
            'tfmovil'       => ['nullable','string', 'max:20'],
            'contacto'      => ['nullable','string', 'max:50'],
            'email'         => ['nullable','email', 'max:50'],
            'web'           => ['nullable','string', 'max:50'],
            'notas1'        => ['nullable','string'],
            'iban'          => ['nullable','iban', 'max:50'],
            'bic'          => ['nullable','bic', 'max:11'],
            'patron'        => ['nullable','string', 'max:50'],
            'fechabaja'     => ['nullable','date'],
            'ref19'         => ['nullable','string', 'max:20'],
            'fpago_id'      => ['nullable','integer'],
            'carpeta_id'    => ['nullable','integer'],
            'efact'          => ['nullable','string', 'max:50'],
            'eusr'          => ['nullable','string', 'max:50'],
            'epass'         => ['nullable','string', 'max:50'],
            'factura'         => ['nullable','boolean'],
        ];

        //\Log::info($this->filled('cif'));

         if ($this->filled('cif'))
            $data['cif'] = ['required',   Rule::unique('clientes')->ignore($this->route('cliente')->id)->where(function ($query) {
                return $query->where('empresa_id', session()->get('empresa')->id);
           })];



        return $data;
    }
}
