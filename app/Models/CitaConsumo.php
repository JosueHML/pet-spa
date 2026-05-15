<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaConsumo extends Model
{
    use HasFactory;

    protected $table = 'cita_consumo';
    protected $primaryKey = 'id_consumo';

    protected $fillable = [
        'id_cita',
        'id_insumo',
        'cantidad',
        'created_at'
    ];
}