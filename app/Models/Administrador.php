<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administradores';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'id_usuario',
        'acceso_reportes',
        'config_sistema'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}