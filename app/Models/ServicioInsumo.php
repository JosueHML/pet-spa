<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioInsumo extends Model
{
    use HasFactory;

    protected $table = 'servicio_insumos';
    protected $primaryKey = 'id_servicio_insumo';

    protected $fillable = [
        'id_servicio',
        'id_insumo',
        'cantidad'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'id_insumo');
    }
}