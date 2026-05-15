<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajero extends Model
{
    use HasFactory;

    protected $table = 'cajeros';
    protected $primaryKey = 'id_cajero';

    protected $fillable = [
        'id_usuario',
        'permisos_pagos'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}