<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'id_mascota',
        'id_groomer',
        'id_servicio',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    // Relación con Mascota
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }

    // Relación con Servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    // Relación con Groomer
    public function groomer()
    {
        return $this->belongsTo(Groomer::class, 'id_groomer');
    }
}