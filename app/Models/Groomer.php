<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groomer extends Model
{
    use HasFactory;

    protected $table = 'groomers';
    protected $primaryKey = 'id_groomer';

    protected $fillable = [
        'id_usuario',
        'especialidad',
        'telefono',
        'turno',
        'max_citas_diarias',
        'capacidad_simultanea',
        'activo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}