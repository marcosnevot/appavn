<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relación con los clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
