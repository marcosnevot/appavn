<!-- Contenedor de la tabla con scroll -->
<div class="table-container" style="max-height: 80vh; width: 100%; overflow-x: auto; overflow-y: auto;">
    <!-- Tabla de tareas -->
    <table class="min-w-full table-auto bg-white dark:bg-gray-800">
        <thead>
            <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Subtipo</th>
                <th>Estado</th>
                <th>Asignado a</th>
                <th>Descripción</th>
                <th>Observaciones</th>
                <th>Archivo</th>
                <th>Facturable</th>
                <th>Facturado</th>
                <th>Precio</th>
                <th>Suplido</th>
                <th>Coste</th>
                <th>Fecha Inicio</th>
                <th>Fecha Vencimiento</th>
                <th>Fecha Imputación</th>
                <th>Tiempo Previsto</th>
                <th>Tiempo Real</th>
                <th style="display: none;">Fecha de Creación</th> <!-- Columna oculta para created_at -->
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $task->id }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->asunto->nombre ?? 'Sin asunto' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->cliente->nombre_fiscal ?? 'Sin cliente' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->tipo->nombre ?? 'Sin tipo' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->subtipo }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->estado }}</td>

                <!-- Mostrar usuarios asignados -->
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                    @if($task->users->isNotEmpty())
                    @foreach($task->users as $user)
                    {{ $user->name }}@if(!$loop->last), @endif
                    @endforeach
                    @else
                    Sin asignación
                    @endif
                </td>

                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($task->descripcion, 50) }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($task->observaciones, 50) }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->archivo ?? 'No disponible' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->facturable ? 'Sí' : 'No' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->facturado ?? 'No facturado' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->precio ?? 'N/A' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->suplido ?? 'N/A' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->coste ?? 'N/A' }}</td>

                <!-- Formatear las fechas -->
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->fecha_inicio ? \Carbon\Carbon::parse($task->fecha_inicio)->format('d/m/Y') : 'Sin fecha' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->fecha_vencimiento ? \Carbon\Carbon::parse($task->fecha_vencimiento)->format('d/m/Y') : 'Sin fecha' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->fecha_imputacion ? \Carbon\Carbon::parse($task->fecha_imputacion)->format('d/m/Y') : 'Sin fecha' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->tiempo_previsto ?? 'N/A' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task->tiempo_real ?? 'N/A' }}</td>
                <td style="display: none;">{{ $task->created_at }}</td> <!-- Campo oculto para created_at -->


            </tr>
            @empty
            <tr>
                <td colspan="21" class="px-4 py-2 text-center text-sm text-gray-500 dark:text-gray-300">No hay tareas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>