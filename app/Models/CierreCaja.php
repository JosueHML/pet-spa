<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    use HasFactory;

    protected $table = 'cierres_caja';
    protected $primaryKey = 'id_cierre';

    protected $fillable = [
        'id_cajero',
        'total_efectivo',
        'total_qr',
        'total_transferencia',
        'total_general',
        'fecha_cierre',
        'observaciones'
    ];

    protected $casts = [
        'fecha_cierre' => 'datetime'
    ];

    public function cajero()
    {
        return $this->belongsTo(Cajero::class, 'id_cajero');
    }
}