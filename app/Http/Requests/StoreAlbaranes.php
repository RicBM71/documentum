<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbaranes extends FormRequest
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
            'ejercicio'      => ['integer'],
            // 'albaran'        => ['required', 'integer'],
            // 'serie'          => ['required', 'String', 'max:3'],
            'fecha_alb'      => ['required', 'Date',],
            'cliente_id'     => ['required', 'integer'],
            'eje_fac'         => ['integer','nullable '],
            'factura'        => ['String', 'nullable','max:20'],
            'fecha_fac'      => ['Date','nullable'],
            'fpago_id'       => ['required', 'integer'],
            'vencimiento_id' => ['required', 'integer'],
            'notificado'     => ['required', 'Boolean'],
            'notas'          => ['String','nullable',],
        ];
    }
}
