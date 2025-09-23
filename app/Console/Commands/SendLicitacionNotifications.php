<?php

namespace App\Console\Commands;

use App\Models\Documento;
use App\Models\DocumentoLegal;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\LicitacionAvisoNotification;
use App\Notifications\LicitacionAvisoNotificationRevalidation;
use Carbon\Carbon;

class SendLicitacionNotifications extends Command
{
    protected $signature = 'documents:send-licitacion-notifications';

    protected $description = 'Envía notificaciones por vigencia y revalidación de documentos';

    public function handle()
    {
        $this->procesarDocumentos(Documento::with(['empresa', 'tipoDeDocumento', 'estado', 'departamento', 'modalidades'])->where('nombre_documento', 'Documento Técnico')->get(), 'fecha_vigencia', 35, LicitacionAvisoNotification::class);
        $this->procesarDocumentos(Documento::with(['empresa', 'tipoDeDocumento', 'estado', 'departamento', 'modalidades'])->where('nombre_documento', 'Documento Técnico')->get(), 'fecha_revalidacion', 7, LicitacionAvisoNotificationRevalidation::class);

        $this->procesarDocumentos(DocumentoLegal::with(['empresa', 'tipoDocumento', 'departamento'])->where('nombre_documento', 'Documento Legal')->get(), 'fecha_vigencia', 35, LicitacionAvisoNotification::class);
        $this->procesarDocumentos(DocumentoLegal::with(['empresa', 'tipoDocumento', 'departamento'])->where('nombre_documento', 'Documento Legal')->get(), 'fecha_revalidacion', 7, LicitacionAvisoNotificationRevalidation::class);

        return 0;
    }

    private function procesarDocumentos($documentos, string $campoFecha, int $diasObjetivo, string $notificacionClass)
    {
        foreach ($documentos as $documento) {
            $fecha = Carbon::parse($documento->$campoFecha)->startOfDay();
            $dias_restantes = now()->startOfDay()->diffInDays($fecha, false);
            $documento->setAttribute('dias_restantes', $dias_restantes);

            if ($dias_restantes === $diasObjetivo) {
                //dump("📄 {$documento->nombre_documento} cumple con {$diasObjetivo} días para {$campoFecha}: Tipo → " . optional($documento->tipoDeDocumento)->nombre_documento);
                $this->enviarNotificacion($documento, $notificacionClass);
            }
        }
    }

    private function enviarNotificacion($documento, string $notificacionClass)
    {
        $usuarios = User::all();

        foreach ($usuarios as $usuario) {
            $usuario->notify(new $notificacionClass($documento));
        }

        if ($documento->departamento && $documento->departamento->email) {
            $documento->departamento->notify(new $notificacionClass($documento));
        }
    }
}
