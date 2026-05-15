<?php

namespace App\Console\Commands;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestReminder extends Command
{
    protected $signature = 'test:reminder';
    protected $description = 'Test para verificar citas próximas';

    public function handle()
    {
        $ahora = Carbon::now();
        $this->info("Hora actual: " . $ahora);

        // Buscar citas pendientes en las próximas 3 horas
        $citas = Cita::where('estado', 'PENDIENTE')
            ->where('fecha_inicio', '>', $ahora)
            ->where('fecha_inicio', '<', $ahora->copy()->addHours(3))
            ->get();

        $this->info("Citas encontradas: " . $citas->count());

        foreach ($citas as $cita) {
            $this->info("Cita ID: {$cita->id_cita}, Fecha: {$cita->fecha_inicio}");
        }

        return Command::SUCCESS;
    }
}