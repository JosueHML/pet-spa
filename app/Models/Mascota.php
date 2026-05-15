<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascotas';
    protected $primaryKey = 'id_mascota';

    protected $fillable = [
        'id_cliente',
        'nombre_mascota',
        'raza',
        'tamanio',
        'factor_tamanio',
        'edad_meses',
        'alergias',
        'vacunas',
        'restricciones'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_mascota');
    }   
}