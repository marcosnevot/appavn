<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $customers;

    public function __construct(Collection $customers)
    {
        $this->customers = $customers->map(function ($customer) {
            return [
                'ID' => $customer->id,
                'Nombre Fiscal' => $customer->nombre_fiscal,
                'NIF' => $customer->nif,
                'Móvil' => $customer->movil,
                'Segundo Teléfono' => $customer->segundo_telefono,
                'Fijo' => $customer->fijo,
                'Persona de Contacto' => $customer->persona_contacto,
                'Email' => $customer->email,
                'Dirección' => $customer->direccion,
                'Código Postal' => $customer->codigo_postal,
                'Población' => $customer->poblacion,
                'Tipo de Cliente' => $customer->tipoCliente ? $customer->tipoCliente->nombre : 'Sin tipo',
                'Clasificación' => $customer->clasificacion ? $customer->clasificacion->nombre : 'Sin clasificación',
                'Tributación' => $customer->tributacion ? $customer->tributacion->nombre : 'Sin tributación',
                'Situación' => $customer->situacion ? $customer->situacion->nombre : 'Sin situación',
                'Datos Bancarios' => $customer->datos_bancarios,
                'Subclase' => $customer->subclase,
                'Puntaje' => $customer->puntaje,
                'Código Sage' => $customer->codigo_sage,
                'Fecha de Creación' => $customer->created_at,
            ];
        });
    }

    public function collection()
    {
        return collect($this->customers);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre Fiscal',
            'NIF',
            'Móvil',
            'Segundo Teléfono',
            'Fijo',
            'Persona de Contacto',
            'Email',
            'Dirección',
            'Código Postal',
            'Población',
            'Tipo de Cliente',
            'Clasificación',
            'Tributación',
            'Situación',
            'Datos Bancarios',
            'Subclase',
            'Puntaje',
            'Código Sage',
            'Fecha de Creación',
        ];
    }
}