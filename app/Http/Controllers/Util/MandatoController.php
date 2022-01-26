<?php

namespace App\Http\Controllers\Util;

use PDF;
use App\Cuenta;
use App\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MandatoController extends Controller
{
    public function index()
    {

        $this->authorize('view', new Carpeta);

        $data = Carpeta::with('archivo')->get();
        //$data = Carpeta::all();

        if (request()->wantsJson())
            return $data;
    }

    public function print($id)
    {

        $empresa  = session()->get('empresa');

        PDF::setHeaderCallback(function($pdf) {
            $file = '@'.(Storage::disk('public')->get('logos/'. session()->get('empresa')->logo));
            $pdf->setJPEGQuality(75);
            $pdf->Image($file, 0, 10, 50, 14, 'JPG', null, 'M', false, 300, 'L', false, false, 0, false, false, false);
            //$pdf->Image($file, 0, 10, 0, 0, 'JPG', null, 'M', false, 300, 'L', false, false, 0, false, false, false);

            // PDF::SetFont('helvetica', 'R', 7);

            // $txt = session()->get('empresa')->razon."\n".
			//        session()->get('empresa')->direccion."\n".
			//        session()->get('empresa')->cpostal." ".session()->get('empresa')->poblacion."\n".
            //        'CIF.: '.session()->get('empresa')->cif;
            // $pdf->Write(0, $txt);

        });

        PDF::setFooterCallback(function($pdf) {

            PDF::SetFont('helvetica', 'R', 10);

            $texto = "TODOS LOS CAMPOS HAN DE SER CUMPLIMENTADOS OBLIGATORIAMENTE.".
                    "UNA VEZ FIRMADA ESTA ORDEN DE DOMICILIACIÓN DEBE SER ENVIADA AL ACREEDOR PARA SU CUSTODIA.\n";


            PDF::Write(0, $texto, $link='', $fill=0, $align='J', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
            PDF::Ln();

            PDF::SetFont('helvetica', 'R', 8);
        });


        $this->setPrepararAlb($empresa);


        $cliente = Cliente::FindOrFail($id);

        // cabecera acreedor
        $this->setCabeceraAcreedor($empresa, $cliente);

        // cabecera deudor

        $this->setCabeceraDeudor($cliente);

        // body albarán, líneas.

        //$this->setLineasAlb($data->albalins);


        //PDF::Write(0, 'Hello Worldd');

        PDF::Output('Mandato.pdf');

        PDF::reset();

    }

    /**
     *
     * @param Model Empresa
     *
     */
    private function setPrepararAlb($empresa){

                // set document information
        PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor($empresa->nombre);
        PDF::SetTitle('Albarán');
        PDF::SetSubject('');

        // set default header data
        //PDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        //PDF::SetFooterMargin(PDF_MARGIN_FOOTER);
        PDF::SetFooterMargin(34);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        // ---------------------------------------------------------

        PDF::SetFont('helvetica', 'BU', 14, '', false);

        // add a page
        PDF::AddPage();

        $txt = "ORDEN DE DOMICILIACIÓN DE ADEUDO SEPA";

        // $x = PDF::getX();
        // $y = PDF::getY();
        // PDF::SetX(15);
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));
        // PDF::SetX($x);
        // PDF::SetY($y);
    }

    /**
     *
     * @param Model Cliente
     *
     */
    private function setCabeceraAcreedor($empresa, $cliente, $cuenta_id=4){

        $cuenta = Cuenta::FindOrFail($cuenta_id);

		// recuadro cliente
		PDF::SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(20, 70, 155)));
		PDF::RoundedRect(10, 45, 186, 40, 3.50, '1111', '');

        PDF::SetFont('helvetica', 'RB', 11, '', false);

        $x = PDF::getX();
        $y = PDF::getY();
        $txt = strtoupper("A rellenar por el acreedor");
        PDF::SetY(38);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));


        PDF::SetFont('helvetica', 'R', 10, '', false);
        PDF::SetY(50);
        $txt = "Referencia de la orden de domiciliación:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+5);
        $txt = "Indentificador del acreedor:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+10);
        $txt = "Nombre del acreedor:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+15);
        $txt = "Dirección:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+20);
        $txt = "Código postal - Población - Provincia:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+25);
        $txt = "País:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));


        PDF::SetFont('helvetica', 'B', 10, '', false);
        $txt = $cliente->ref19;
        PDF::SetY(50);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+5);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $cuenta->sufijo_adeudo, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+10);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $empresa->razon, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(50+15);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $empresa->direccion, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));


		$txt = $empresa->cpostal.' - '.$empresa->poblacion. ' - '.$empresa->provincia;
		PDF::SetY(50+20);
        PDF::SetX($x + 70);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        $txt = 'España';
		PDF::SetY(50+25);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetFont('helvetica', 'R', 11, '', false);
        $txt = "Mediante la firma de esta orden de domiciliación, el deudor autoriza (A) al acreedor a enviar instrucciones a la ent idad del deudor para adeudar su cuentay (B) a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. Como parte de sus derechos, el deudor está legitimado alreembolso por su entidad en los términos y condiciones del contrato suscrito con la misma. La solicitud de reembolso deberá efectuarse dentro de las ocho semanas que siguen a la fecha de adeudo en cuenta. Puede obtener información adicional sobre sus derechos en su entidad financiera.\n";
        PDF::SetY(48+45);
        PDF::SetX($x);
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='J', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

    }

    /**
     *
     * @param Model Albaran
     *
     */
    private function setCabeceraDeudor($cliente){

        PDF::SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(20, 70, 155)));
		PDF::RoundedRect(10, 140, 186, 50, 3.50, '1111', '');

        $x = PDF::getX();

        PDF::SetFont('helvetica', 'RB', 11, '', false);
        $txt = strtoupper("A rellenar por el deudor");
        PDF::SetY(130);
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetFont('helvetica', 'R', 10, '', false);
        PDF::SetY(146);
        $txt = "Nombre del deudor:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+5);
        $txt = "Dirección del deudor:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+10);
        $txt = "Código postal - Población - Provincia:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+15);
        $txt = "Swift BIC:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+20);
        $txt = "Número de cuenta - IBAN:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+25);
        $txt = "Tipo de Pago:";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));


        PDF::SetY(146+85);
        PDF::SetX($x + 70);
        $txt = "Firma del deudor";
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));


        PDF::SetFont('helvetica', 'B', 10, '', false);
        $txt = $cliente->razon;
        PDF::SetY(146);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+5);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $cliente->direccion, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+10);
        PDF::SetX($x + 70);

		$txt = $cliente->cpostal.' - '.$cliente->poblacion. ' - '.$cliente->provincia;
		PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

		PDF::SetY(146+15);
        PDF::SetX($x + 70);
        PDF::Write($h=0, $cliente->bic, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+20);
        PDF::SetX($x + 70);
        PDF::Write($h=0, getIbanPrint($cliente->iban), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+25);
        PDF::SetX($x + 70);
        PDF::Write($h=0, "RECURRENTE", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));

        PDF::SetY(146+50);
        PDF::SetX($x + 10);


        setlocale(LC_ALL, 'es_ES');
        $fecha = Carbon::parse(date('Y-m-d'));
        $fecha->format("F"); // Inglés.
        $mes = $fecha->formatLocalized('%B');// mes en idioma español

        PDF::SetFont('helvetica', 'R', 10, '', false);
        $txt = ucfirst(strtolower($cliente->poblacion)).', '.date('j').' de '.$mes.' de '.date('Y').'.';
        PDF::Write($h=0, $txt, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $w=0, array(100,100,10));
    }


}
