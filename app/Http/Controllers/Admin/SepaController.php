<?php

namespace App\Http\Controllers\Admin;

use App\Cuenta;
use App\Remesa;
use Illuminate\Http\Request;
use Digitick\Sepa\GroupHeader;
use App\Http\Controllers\Controller;
use Digitick\Sepa\PaymentInformation;
use Digitick\Sepa\DomBuilder\DomBuilderFactory;
use Digitick\Sepa\TransferFile\CustomerCreditTransferFile;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;
use Digitick\Sepa\TransferInformation\CustomerCreditTransferInformation;


class SepaController extends Controller
{
    public function index(){

        // if (!auth()->user()->hasRole('Admin'))
        //     abort(403,'No Autorizado');

        // $transferencias =  Remesa::with(['cliente'])->get();
        // $remesa = $this->generarTransfer($transferencias,2,'2019-06-27');
        // return $remesa;

        if (request()->wantsJson())
            return [
                'cuentas'  => Cuenta::selCuentas(),
            ];
    }

    public function transfer(Request $request){

        $data = $request->validate([
            'cuenta_id'=>'required|integer',
            'fecha'=>'required|date'
        ]);


        $transferencias =  Remesa::with(['cliente'])->remesar($data['fecha'])->get();

        if ($transferencias->count()==0)
            abort(404,'No hay transferencias para remesar');

        $remesa = $this->generarTransfer($transferencias,$data['cuenta_id'],$data['fecha']);

        if (request()->wantsJson())
            return [
                'xml'       => $remesa['xml'],
                'importe'   => $remesa['importe'],
                'transferencias'   => $remesa['transferencias']
            ];

    }

     /**
     * Vamos a generar el fichero de remesa.
     */
    private function generarTransfer($data, $cuenta_id, $fecha){


        $cuenta = Cuenta::find($cuenta_id);

        //$idPayment = session()->get('empresa')->cif;
        $idPayment = session()->get('empresa')->cif.date('Ymdhis');


                // Create the initiating information
        $groupHeader = new GroupHeader($idPayment, session()->get('empresa')->razon);
        $idSufijo=$cuenta->sufijo_transf;

        $groupHeader->setInitiatingPartyId($idSufijo);
        //$groupHeader->setInitiatingPartyId($cuenta->sepa);

        $sepaFile = new CustomerCreditTransferFile($groupHeader);

        $imp_total_remesa = $transferencias = 0;
        foreach ($data as $row){

            $tr = [
                'enviada' => true,
                'iban_cargo' => $cuenta->iban,
                'bic_cargo' => $cuenta->bic,
                'username' => session()->get('username')
            ];
            $row->update($tr);

            $transferencias++;

            $imp_total_remesa += $row->importe;

            $transfer = new CustomerCreditTransferInformation(
                $row->importe, // Amount
                $row->iban_abono, //IBAN of creditor
                $row->cliente->razon //Name of Creditor
            );
            $transfer->setBic($row->bic_abono); // Set the BIC explicitly
            $transfer->setRemittanceInformation($row->concepto);
            $transfer->setEndToEndIdentification(uniqid());

            // Create a PaymentInformation the Transfer belongs to
            $payment = new PaymentInformation(
                $idPayment,
                $cuenta->iban, // IBAN the money is transferred from
                $cuenta->bic,  // BIC
                session()->get('empresa')->razon // Debitor Name
            );

            // poner est para indicar que es nómina
            // SALA: Salario
            // OTHR: Otros
            // SUPP: Pagos a proveedores
            $payment->setCategoryPurposeCode($row->categoria);
            // It's possible to add multiple Transfers in one Payment
            $payment->addTransfer($transfer);

            // It's possible to add multiple payments to one SEPA File
            $sepaFile->addPaymentInformation($payment);
        }




        // Attach a dombuilder to the sepaFile to create the XML output
        //$domBuilder = DomBuilderFactory::createDomBuilder($sepaFile);

        // Or if you want to use the format 'pain.001.001.03' instead
       $domBuilder = DomBuilderFactory::createDomBuilder($sepaFile, 'pain.001.001.03');

        $xml = $domBuilder->asXml();



        // $customerCredit = TransferFileFacadeFactory::createCustomerCredit(session()->get('empresa')->cif, session()->get('empresa')->razon);

        // $idPayment = session()->get('empresa')->cif."000";

        // $customerCredit->addPaymentInfo($idPayment, array(
        //     'id'                      => $idPayment,
        //     'debtorName'              => session()->get('empresa')->razon,
        //     'debtorAccountIBAN'       => $cuenta->iban,
        //     'debtorAgentBIC'          => $cuenta->bic,
        // ));

        // $imp_total_remesa = $transferencias = 0;
        // foreach ($data as $row){

        //     $t = [
        //         'enviada' => true,
        //         'username' => session()->get('username')
        //     ];
        //     $row->update($t);

        //     $transferencias++;
        //     // create a payment, it's possible to create multiple payments,
        //     // "firstPayment" is the identifier for the transactions

        //     $imp_total_remesa += $row->importe;

        //         // Add a Single Transaction to the named payment
        //     $customerCredit->addTransfer($idPayment, array(
        //         'amount'                  => $row->importe,
        //         'creditorIban'            => $row->iban_abono,
        //         'creditorBic'             => $row->bic_abono,
        //         'creditorName'            => $row->cliente->razon,
        //         'remittanceInformation'   => $row->concepto
        //     ));

        // }

        // $xml = $customerCredit->asXML();

        return [
            'xml' => $xml,
            'importe' => $imp_total_remesa,
            'transferencias' => $transferencias
        ];

        // \Storage::disk('local')->put('remesa.xml',$xml);

        // \Storage::disk('local')->download('remesa.xml');

    }

     public function recibos(Request $request){

        if (!auth()->user()->hasRole('Admin'))
            abort(403,'No Autorizado');

        $data = $request->validate([
            'cuenta_id'=>'required|integer',
            'fecha'=>'required|date'
        ]);


        $recibos =  Remesa::with(['cliente'])->recibos($data['fecha'])->get();

        if ($recibos->count() == 0)
            abort(404,'No hay facturas para remesar');

        $remesa = $this->generarRecibos($recibos,$data['cuenta_id'],$data['fecha']);

        if (request()->wantsJson())
            return [
                'xml'       => $remesa['xml'],
                'importe'   => $remesa['importe'],
                'adeudos'   => $remesa['adeudos']
            ];

    }

    /**
     * Vamos a generar el fichero de remesa.
     */
    private function generarRecibos($data, $cuenta_id, $fecha_cob){


        $cuenta = Cuenta::find($cuenta_id);

        // firstPayment,
        $PmtInfId =  session()->get('empresa')->cif.'000'.date('Ymdhisv');

        $header = new GroupHeader(date('Y-m-d-H-i-s'), session()->get('empresa')->razon);
        $header->setInitiatingPartyId($cuenta->sufijo_adeudo);

        $directDebit = TransferFileFacadeFactory::createDirectDebitWithGroupHeader($header, 'pain.008.001.02');
        //$directDebit = TransferFileFacadeFactory::createDirectDebit(session()->get('empresa')->cif.'000', $cuenta->sufijo_adeudo, 'pain.008.001.02');

        //ReqdExctnDt -- ReqdColltnDt --
        // create a payment, it's possible to create multiple payments,
        // "firstPayment" is the identifier for the transactions
        // This creates a one time debit. If needed change use ::S_FIRST, ::S_RECURRING or ::S_FINAL respectively
        //$directDebit->addPaymentInfo($PmtInfId, array(
        $directDebit->addPaymentInfo($PmtInfId, array(
            'id'                    => $PmtInfId,
            // 'dueDate'               => new \DateTime(), // optional. Otherwise default period is used
            'dueDate'               => $fecha_cob,
            'creditorName'          => session()->get('empresa')->razon,
            'creditorAccountIBAN'   => $cuenta->iban,
            'creditorAgentBIC'      => $cuenta->bic,
           // 'seqType'               => PaymentInformation::S_ONEOFF,
            'seqType'               => PaymentInformation::S_RECURRING,
            'creditorId'            => $cuenta->sufijo_adeudo,
            'localInstrumentCode'   => 'CORE' // default. optional.
        ));

        $imp_total_remesa = $adeudos = 0;
        foreach ($data as $row){

            $imp_total_remesa += $row->importe;
            $adeudos++;

            $tr = [
                'enviada' => true,
                'iban_cargo' => $cuenta->iban,
                'bic_cargo' => $cuenta->bic,
                'username' => session()->get('username')
            ];
            $row->update($tr);

            $directDebit->addTransfer($PmtInfId, array(
                'amount'                => $row->importe,
                'debtorIban'            => $row->iban_abono,
                'debtorBic'             => $row->bic_abono,
                'debtorName'            => $row->cliente->razon,
                'debtorMandate'         => $row->ref19,
                'debtorMandateSignDate' => date('Y-m-d'),
             //   'debtorMandateSignDate' => $row->fecha_fac,
                'remittanceInformation' => $row->concepto,
                'endToEndId'            => 'ID0000'.$row->id
            ));

        }

        $xml = $directDebit->asXML();

        return [
            'xml' => $xml,
            'importe' => $imp_total_remesa,
            'adeudos' => $adeudos
        ];

        // \Storage::disk('local')->put('remesa.xml',$xml);

        // \Storage::disk('local')->download('remesa.xml');

    }
}
