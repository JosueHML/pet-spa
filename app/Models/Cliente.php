<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_usuario',
        'ci',
        'telefono',
        'direccion',
        'puntos_frecuencia',
        'preferencia_notificacion'
    ];

    // Encriptar teléfono al guardar
    public function setTelefonoAttribute($value)
    {
        $this->attributes['telefono'] = $value;
        $this->attributes['telefono_encrypted'] = Crypt::encryptString($value);
    }

    // Encriptar dirección al guardar
    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = $value;
        $this->attributes['direccion_encrypted'] = Crypt::encryptString($value);
    }

    // Desencriptar teléfono al leer
    public function getTelefonoDecryptedAttribute()
    {
        return $this->telefono_encrypted ? Crypt::decryptString($this->telefono_encrypted) : $this->telefono;
    }

    // Desencriptar dirección al leer
    public function getDireccionDecryptedAttribute()
    {
        return $this->direccion_encrypted ? Crypt::decryptString($this->direccion_encrypted) : $this->direccion;
    }

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'id_cliente');
    }
}