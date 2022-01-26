<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Factura extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //\Log::info($this->data['alb']->factura.'.pdf');

        $file = 'F'.$this->data['alb']->factura.'.pdf';

        return $this->markdown('emails.factura')
                    ->with('data', $this->data)
                    ->subject("Factura ".$this->data['alb']->factura)
                    ->from($this->data['from'],$this->data['razon'])
                    ->attach(storage_path('facturas/'.$file), [
                        'as' => $file,
                        'mime' => 'application/pdf'
                ]);
    }
}
