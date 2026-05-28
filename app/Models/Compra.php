<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    
    protected $table = 'compras';
    protected $primaryKey = 'id_compra';
    
    protected $fillable = [
        'id_proveedor', 'numero_factura', 'fecha_compra', 
        'subtotal', 'impuesto', 'total', 'estado', 'observaciones'
    ];
    
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }
    
    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class, 'id_compra');
    }
}