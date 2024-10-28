<?php

namespace App\Events;

use App\Models\Cliente;
use App\Models\Tarea;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $customer;

    public function __construct(Cliente $customer)
    {
        // Cargar relaciones necesarias como en TaskCreated
        $this->customer = $customer->load('clasificacion', 'situacion', 'tipoCliente', 'tributacion', 'users');
    }

    public function broadcastAs()
    {
        return 'CustomerUpdated';
    }


    public function broadcastOn()
    {
        return new Channel('customers');
    }

    public function broadcastWith()
    {
        Log::debug('Datos que se están emitiendo en CustomerUpdated: ', $this->customer->toArray());
    
        return [
            'customer' => [
                'id' => $this->customer->id,
                'nombre_fiscal' => $this->customer->nombre_fiscal,
                'nif' => $this->customer->nif,
                'movil' => $this->customer->movil,
                'fijo' => $this->customer->fijo,
                'email' => $this->customer->email,
                'direccion' => $this->customer->direccion,
                'codigo_postal' => $this->customer->codigo_postal,
                'poblacion' => $this->customer->poblacion,
                'datos_bancarios' => $this->customer->datos_bancarios,
                'responsable' => $this->customer->responsable ? $this->customer->responsable->name : null,
                'tipo_cliente' => $this->customer->tipoCliente ? $this->customer->tipoCliente->nombre : null,
                'clasificacion' => $this->customer->clasificacion ? $this->customer->clasificacion->nombre : null,
                'tributacion' => $this->customer->tributacion ? $this->customer->tributacion->nombre : null,
                'situacion' => $this->customer->situacion ? $this->customer->situacion->nombre : null,
                'subclase' => $this->customer->subclase,
                'puntaje' => $this->customer->puntaje,
                'codigo_sage' => $this->customer->codigo_sage,
                'users' => $this->customer->users->map(function ($user) {
                    return $user->name;
                })->toArray(),
            ]
        ];
    }
    
}
