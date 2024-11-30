<?php

namespace App\Models;

use App\Events\TaskUpdated;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';
    protected $fillable = [
        'asunto_id',
        'tipo_id',
        'subtipo',
        'estado',
        'cliente_id',
        'descripcion',
        'observaciones',
        'archivo',
        'facturable',
        'facturado',
        'precio',
        'suplido',
        'coste',
        'fecha_inicio',
        'fecha_vencimiento',
        'fecha_imputacion',
        'fecha_planificacion',
        'tiempo_previsto',
        'tiempo_real'
    ];


    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con el asunto
    public function asunto()
    {
        return $this->belongsTo(Asunto::class);
    }

    // Relación con el tipo
    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    // Relación con los usuarios asignados (muchos a muchos)
    public function users()
    {
        return $this->belongsToMany(User::class, 'tarea_user', 'tarea_id', 'user_id');
    }
}
