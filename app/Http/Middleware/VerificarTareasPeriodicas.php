<?php

namespace App\Http\Middleware;

use App\Jobs\GenerarTareaJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;

class VerificarTareasPeriodicas
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ejecutar el Job que verifica si se deben generar tareas periódicas
        dispatch(new GenerarTareaJob());
        return $next($request);
    }
}
