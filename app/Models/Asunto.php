<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asunto extends Model
{
    use HasFactory;
    protected $table = 'asuntos';

    protected $fillable = ['nombre'];

    // Relación con las tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }
}
