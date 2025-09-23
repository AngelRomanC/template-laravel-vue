<?php

namespace App\Mail;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LicitacionAviso extends Mailable
{
    use Queueable, SerializesModels;

    public $documento;

    public function __construct(Documento $documento)
    {
        $this->documento = $documento;
    }

    public function build()
    {
        return $this->subject('Aviso de Licitación Próxima a Vencer')
                    ->view('mail.licitacion_aviso') // Asegurar que la ruta es correcta
                    ->with([
                        'documento' => $this->documento,
                    ]);
    }
}
