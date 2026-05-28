<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;
    
    protected $table = 'compra_detalles';
    protected $primaryKey = 'id_detalle';
    
    protected $fillable = [
        'id_compra', 'tipo_producto', 'id_item', 'cantidad', 'precio_unitario', 'subtotal'
    ];
    
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }
    
    public function producto()
    {
        if ($this->tipo_producto == 'PRODUCTO') {
            return $this->belongsTo(Producto::class, 'id_item');
        }
        return null;
    }
    
    public function insumo()
    {
        if ($this->tipo_producto == 'INSUMO') {
            return $this->belongsTo(Insumo::class, 'id_item');
        }
        return null;
    }
}