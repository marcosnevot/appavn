<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; 
    protected $fillable = [
        'nombre_fiscal',
        'nif',
        'movil',
        'segundo_telefono',
        'fijo',
        'persona_contacto',
        'email',
        'direccion',
        'codigo_postal',
        'poblacion',
        'datos_bancarios',
        'tipo_cliente_id',
        'tributacion_id',
        'clasificacion_id',
        'subclase',
        'situacion_id',
        'puntaje',
        'codigo_sage'
    ];

    // Relación con el tipo de cliente
    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class);
    }

    // Relación con la tributación
    public function tributacion()
    {
        return $this->belongsTo(Tributacion::class);
    }

    // Relación con la clasificación
    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    // Relación con la situación
    public function situacion()
    {
        return $this->belongsTo(Situacion::class);
    }

    // Relación con los responsables (usuarios)
    public function users()
    {
        return $this->belongsToMany(User::class, 'cliente_user', 'cliente_id', 'user_id');
    }

    // Relación con las tareas asociadas al cliente
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }
}
