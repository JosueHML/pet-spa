<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaBloqueo extends Model
{
    use HasFactory;

    protected $table = 'agenda_bloqueos';
    protected $primaryKey = 'id_bloqueo';

    protected $fillable = [
        'id_groomer',
        'fecha_bloqueo',
        'tipo',
        'motivo',
        'alcance'
    ];

    public function groomer()
    {
        return $this->belongsTo(Groomer::class, 'id_groomer');
    }
}