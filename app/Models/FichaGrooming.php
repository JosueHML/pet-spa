<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaGrooming extends Model
{
    use HasFactory;

    protected $table = 'fichas_grooming';
    protected $primaryKey = 'id_ficha';

    protected $fillable = [
        'id_cita',
        'estado_ficha',
        'nudos',
        'pulgas',
        'heridas',
        'observaciones',
        'recomendaciones',
        'foto_antes',
        'foto_despues',
        'checklist_json'
    ];

    protected $casts = [
        'checklist_json' => 'array',
        'fecha_cierre' => 'datetime'
    ];

    // Relación con Cita
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita');
    }
}