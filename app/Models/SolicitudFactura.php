<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudFactura extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_factura';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'id_cliente',
        'carrito_data',
        'total',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'carrito_data' => 'array'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}