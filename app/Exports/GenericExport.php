<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class GenericExport implements FromCollection
{
    private $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function collection()
    {
        $modelClass = "App\\Models\\" . ucfirst($this->model);
        if (class_exists($modelClass)) {
            return $modelClass::select('nombre')->get();
        }

        return new Collection([]);
    }
}
