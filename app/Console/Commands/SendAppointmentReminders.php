<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Models\Notificacion;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMail;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Envía recordatorios de citas programadas';

    public function handle()
    {
        $now = Carbon::now();
        $total = 0;

        $this->info("Hora actual: " . $now);

        // =============================================
        // RECORDATORIOS 24 HORAS ANTES
        // =============================================
        $inicio24h = $now->copy()->addHours(24)->subMinutes(30);
        $fin24h = $now->copy()->addHours(24)->addMinutes(30);

        $citas24h = Cita::with(['mascota.cliente.user'])
            ->where('estado', 'PENDIENTE')
            ->whereBetween('fecha_inicio', [$inicio24h, $fin24h])
            ->get();

        foreach ($citas24h as $cita) {
            $cliente = $cita->mascota->cliente;
            $user = $cliente->user;

            // Guardar en base de datos
            Notificacion::create([
                'id_usuario' => $user->id,
                'tipo' => 'RECORDATORIO_24H',
                'mensaje' => "🔔 Recordatorio: Tu mascota {$cita->mascota->nombre_mascota} tiene una cita mañana a las {$cita->fecha_inicio->format('H:i')}.",
                'canal' => 'EMAIL',
                'destino' => $user->email,
                'estado' => 'ENVIADO'
            ]);

            // Enviar email real
            try {
                Mail::to($user->email)->send(new NotificacionMail(
                    "🔔 RECORDATORIO DE CITA\n\n" .
                    "Mascota: {$cita->mascota->nombre_mascota}\n" .
                    "Fecha: " . $cita->fecha_inicio->format('d/m/Y') . "\n" .
                    "Hora: " . $cita->fecha_inicio->format('H:i') . "\n\n" .
                    "Por favor, confirma tu asistencia.",
                    'RECORDATORIO_24H',
                    $cita->mascota->nombre_mascota
                ));
                
                sleep(1); // Espera 1 segundo entre correos para evitar límite de Mailtrap
                
            } catch (\Exception $e) {
                Log::error("Error enviando email 24h: " . $e->getMessage());
            }

            $total++;
            $this->info("Recordatorio 24h enviado para cita ID: {$cita->id_cita} a {$user->email}");
        }

        // =============================================
        // RECORDATORIOS 2 HORAS ANTES
        // =============================================
        $inicio2h = $now->copy()->addHours(2)->subMinutes(30);
        $fin2h = $now->copy()->addHours(2)->addMinutes(30);

        $citas2h = Cita::with(['mascota.cliente.user'])
            ->where('estado', 'PENDIENTE')
            ->whereBetween('fecha_inicio', [$inicio2h, $fin2h])
            ->get();

        foreach ($citas2h as $cita) {
            $cliente = $cita->mascota->cliente;
            $user = $cliente->user;

            // Guardar en base de datos
            Notificacion::create([
                'id_usuario' => $user->id,
                'tipo' => 'RECORDATORIO_2H',
                'mensaje' => "⏰ Recordatorio: Tu mascota {$cita->mascota->nombre_mascota} tiene una cita en 2 horas.",
                'canal' => 'EMAIL',
                'destino' => $user->email,
                'estado' => 'ENVIADO'
            ]);

            // Enviar email real
            try {
                Mail::to($user->email)->send(new NotificacionMail(
                    "⏰ RECORDATORIO DE CITA\n\n" .
                    "Mascota: {$cita->mascota->nombre_mascota}\n" .
                    "Tu cita es en 2 horas.\n\n" .
                    "¡Te esperamos!",
                    'RECORDATORIO_2H',
                    $cita->mascota->nombre_mascota
                ));
                
                sleep(1); // Espera 1 segundo entre correos para evitar límite de Mailtrap
                
            } catch (\Exception $e) {
                Log::error("Error enviando email 2h: " . $e->getMessage());
            }

            $total++;
            $this->info("Recordatorio 2h enviado para cita ID: {$cita->id_cita} a {$user->email}");
        }

        $this->info("Total recordatorios enviados: {$total}");
        return Command::SUCCESS;
    }
}