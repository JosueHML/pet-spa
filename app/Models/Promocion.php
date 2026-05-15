<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';
    protected $primaryKey = 'id_promocion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'valor_descuento',
        'compra_minima',
        'fecha_inicio',
        'fecha_fin',
        'aplica_a',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean'
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'promocion_productos', 'id_promocion', 'id_producto');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'promocion_servicios', 'id_promocion', 'id_servicio');
    }

    public function isActive()
    {
        $today = now()->toDateString();
        return $this->activo && $this->fecha_inicio <= $today && $this->fecha_fin >= $today;
    }

    public function aplicarDescuento($monto)
    {
        if (!$this->isActive()) {
            return $monto;
        }

        if ($this->tipo == 'PORCENTAJE') {
            return $monto - ($monto * $this->valor_descuento / 100);
        } elseif ($this->tipo == 'MONTO_FIJO') {
            return $monto - $this->valor_descuento;
        }

        return $monto;
    }
}