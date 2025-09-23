<?php

namespace App\Console\Commands;

use App\Models\Documento;
use App\Models\DocumentoLegal;
use App\Models\User;
use App\Mail\LicitacionAviso; // Asegúrate de tener el Mailable
use App\Notifications\LicitacionAvisoNotification; // La notificación
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class SendLicitacionReminder extends Command
{
    protected $signature = 'documents:send-licitacion-reminder';
    protected $description = 'Envía recordatorios de licitación y notificaciones a los usuarios.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $documentos = Documento::with(['empresa', 'tipoDeDocumento', 'estado', 'departamento', 'modalidades'])
            ->where('nombre_documento', 'Documento Técnico')
            ->get(); // Cambia paginate por get para procesar todos

        $documentosLegal = DocumentoLegal::with(['empresa', 'tipoDocumento', 'departamento'])
            ->where('nombre_documento', 'Documento Legal')
            ->get();

        foreach ($documentos as $documento) {
            $documento->setAttribute(
                'dias_restantes',
                now()->startOfDay()->diffInDays(
                    Carbon::parse($documento->fecha_vigencia)->startOfDay(),
                    false
                )
            );

            if ($documento->dias_restantes === 35) {
                $this->enviarCorreoLicitacion($documento);
            }
        }
        
        foreach ($documentosLegal as $documento) {
            $documento->setAttribute(
                'dias_restantes', 
                now()->startOfDay()->diffInDays(
                    Carbon::parse($documento->fecha_vigencia)->startOfDay(), 
                    false
                )
            );
        
            if ($documento->dias_restantes === 35) {
                $this->enviarCorreoLicitacion($documento);
            }
        }

        return 0;
    }
    private function enviarCorreoLicitacion($documento)
    {
        $usuarios = User::all(); // Obtener todos los usuarios

        foreach ($usuarios as $usuario) {
            // Mail::to($usuario->email)->send(new LicitacionAviso($documento));
            $usuario->notify(new LicitacionAvisoNotification($documento));
        }
        //$documento->departamento->notify(new LicitacionAvisoNotification($documento));
          
        // Enviar también al departamento responsable del documento
        if ($documento->departamento && $documento->departamento->email) {
            $documento->departamento->notify(new LicitacionAvisoNotification($documento));
            //dd('Enviado al departamento:', $documento->departamento->email);

        }

    }
}
