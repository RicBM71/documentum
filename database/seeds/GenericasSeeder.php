<?php

use App\Iva;
use App\Fpago;
use App\Cuenta;
use App\Archivo;
use App\Carpeta;
use App\Empresa;
use App\Contador;
use App\Retencion;
use Carbon\Carbon;
use App\Vencimiento;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;


class GenericasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Retencion::truncate();
        Iva::truncate();
        Archivo::truncate();
        Carpeta::truncate();
        Empresa::truncate();
        Fpago::truncate();
        Vencimiento::truncate();

        Contador::truncate();

        DB::table('empresa_user')->truncate();


        $reg = new Retencion;
        $reg->nombre = "IRPF ";
        $reg->importe = 15;
        $reg->save();

        $reg = new Iva;
        $reg->nombre = "General ";
        $reg->importe = 21;
        $reg->save();

        $emp = new Empresa;
        $emp->nombre = "Sanaval";
        $emp->razon = "Sanaval Fisioterapia SL";
        $emp->cif="B82848417";
        $emp->titulo = "Sanaval";
        $emp->logo = "logo.jpg";
        $emp->certificado = "sntfirma.crt";
        $emp->passwd_cer="delta00";
        $emp->path_archivo='sanaval';
        $emp->save();


        $emp = new Empresa;
        $emp->nombre = "Sanaval";
        $emp->razon = "Sanaval Tecnología SL";
        $emp->cif="B83667402";
        $emp->titulo = "Sanaval";
        $emp->logo = "logo.jpg";
        $emp->certificado = "sntfirma.crt";
        $emp->passwd_cer="delta00";
        $emp->path_archivo='sanatec';
        $emp->save();

        $json = File::get("database/data/carpetas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Archivo::create(array(
             'id' => $obj->id,
             'nombre' => $obj->nombre,
             'color' => $obj->color,
             'created_at'=> $obj->created_at,
             'updated_at'=> $obj->updated_at
           ));
        }

        DB::table('empresa_user')->insert(
            ['empresa_id' => 1, 'user_id' => '1']
        );

        // $carpeta = new Carpeta;
        // $carpeta->empresa_id = 1;
        // $carpeta->archivo_id = 1;
        // $carpeta->nombre = "Carpeta 1";
        // $carpeta->save();

        $fp = new Fpago;
        $fp->nombre = "Transferencia";
        $fp->save();
        $fp = new Fpago;
        $fp->nombre = "Recibo Domiciliado";
        $fp->save();
        $fp = new Fpago;
        $fp->nombre = "Contado";
        $fp->save();


        $con = new Contador;
        $con->ejercicio = date('Y');
        $con->empresa_id = 1;
        $con->seriealb="ALB";
        $con->albaran=1000;
        $con->seriefac="F";
        $con->factura=1000;
        $con->serieabo="AB";
        $con->abono=1000;

        $con->save();

        $vto = new Vencimiento;
        $vto->nombre = "Fecha Factura";
        $vto->save();

        $cuenta = new Cuenta;
        $cuenta->empresa_id = 2;
        $cuenta->nombre = "Santander";
        $cuenta->iban="ES1500493102912114149064";
        $cuenta->bic="BSCHESMMXXX";
        $cuenta->sufijo_adeudo="ES35001B83667402";

        $cuenta->save();

        //SELECT id,empresa,carpeta,nombre,'red',concat('emp',empresa),1,concat(sysfum," ", syshum),concat(sysfum," ", syshum) FROM `clientes`
        //SELECT id,empresa,cliente,carpeta,fecha,texto,1,sysusr,concat(sysfum," ",syshum),concat(sysfum," ",syshum) FROM `documentos`

    }
}
