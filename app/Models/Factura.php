<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';
    protected $primaryKey = 'id_factura';

    protected $fillable = [
        'id_cita',
        'id_cliente',
        'id_cajero',
        'total',
        'monto_total',
        'numero_factura',
        'metodo_pago',
        'estado_pago',
        'fecha_emision'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function cajero()
    {
        return $this->belongsTo(Cajero::class, 'id_cajero');
    }
}