<?php

namespace App\Events;

use App\Models\Cliente;
use App\Models\Tarea;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CustomerCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $customer;

    public function __construct(Cliente $customer)
    {
        // Aquí, cargar relaciones necesarias
        $this->customer = $customer->load('clasificacion', 'situacion', 'tipoCliente', 'tributacion', 'users');
    }

    public function broadcastOn()
    {
        return new Channel('customers');
    }
}
