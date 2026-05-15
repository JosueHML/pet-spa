<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';  // 👈 ESTA LÍNEA ES CLAVE
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'precio',
        'duracion_minutos'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio');
    }
}