@extends('layouts.app')

@section('content')
<div class="header-tareas">
    <h2 class="title">Tareas</h2>
    <button id="new-task-button" class="btn-new-task">Nueva Tarea</button>
</div>


<!-- Contenedor de la tabla con scroll -->
<div class="table-container" style="max-height: 80vh; width: 78vw; overflow-x: auto; overflow-y: auto;">
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


            </tr>
            @empty
            <tr>
                <td colspan="21" class="px-4 py-2 text-center text-sm text-gray-500 dark:text-gray-300">No hay tareas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Formulario de nueva tarea en un menú desplegable -->
<div id="task-form" class="task-form hide">
    <h3 class="form-title">Nueva Tarea</h3>
    <!-- Formulario para añadir tareas -->
    <form method="POST" id="add-task-form" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group">
                <label for="cliente_id">Cliente:</label>
                <select name="cliente_id" id="cliente_id" required>
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre_fiscal }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="asunto_id">Asunto:</label>
                <select name="asunto_id" id="asunto_id" required>
                    @foreach($asuntos as $asunto)
                    <option value="{{ $asunto->id }}">{{ $asunto->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_id">Tipo de Tarea:</label>
                <select name="tipo_id" id="tipo_id">
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subtipo">Subtipo:</label>
                <select name="subtipo" id="subtipo">
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ENPROGRESO">En Progreso</option>
                    <option value="COMPLETADA">Completada</option>
                </select>
            </div>
        </div>

        <!-- Fila 2: Asignado a, Archivo, Descripción, Observaciones -->
        <div class="form-row">
            <div class="form-group">
                <label for="users">Asignado a:</label>
                <select name="users[]" id="users" multiple>
                    @foreach($usuarios as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="archivo">Archivo:</label>
                <input type="text" name="archivo" id="archivo">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" rows="2"></textarea>
            </div>
        </div>

        <!-- Fila 3: Facturable, Facturado, Precio, Suplido, Coste -->
        <div class="form-row">
            <div class="form-group">
                <label for="facturable">Facturable:</label>
                <input type="checkbox" name="facturable" id="facturable" value="1">
            </div>

            <div class="form-group">
                <label for="facturado">Facturado:</label>
                <input type="text" name="facturado" id="facturado">
            </div>

            <div class="form-group">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" name="precio" id="precio">
            </div>

            <div class="form-group">
                <label for="suplido">Suplido (€):</label>
                <input type="number" step="0.01" name="suplido" id="suplido">
            </div>

            <div class="form-group">
                <label for="coste">Coste (€):</label>
                <input type="number" step="0.01" name="coste" id="coste">
            </div>
        </div>

        <!-- Fila 4: Fecha Inicio, Vencimiento, Imputación, Tiempo Previsto, Tiempo Real -->
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio">
            </div>

            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento">
            </div>

            <div class="form-group">
                <label for="fecha_imputacion">Fecha de Imputación:</label>
                <input type="date" name="fecha_imputacion" id="fecha_imputacion">
            </div>

            <div class="form-group">
                <label for="tiempo_previsto">Tiempo Previsto (Horas):</label>
                <input type="number" step="0.1" name="tiempo_previsto" id="tiempo_previsto">
            </div>

            <div class="form-group">
                <label for="tiempo_real">Tiempo Real (Horas):</label>
                <input type="number" step="0.1" name="tiempo_real" id="tiempo_real">
            </div>
        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit">Añadir Tarea</button>
            <button type="button" id="close-task-form" class="btn-close">Cerrar</button>
        </div>
    </form>
</div>





@endsection

@section('scripts')

<script>
    // Añadir el event listener para manejar el envío del formulario
    document.addEventListener('DOMContentLoaded', function() {
        console.log('El script ha sido cargado correctamente.');

        const taskForm = document.getElementById('task-form');

        // Mostrar el formulario cuando se pulsa el botón de "Nueva Tarea"
        document.getElementById('new-task-button').addEventListener('click', function() {
            taskForm.style.display = 'block';
            setTimeout(() => {
                taskForm.classList.remove('hide');
                taskForm.classList.add('show');
            }, 10);
        });

        // Ocultar el formulario cuando se pulsa el botón de cerrar
        document.getElementById('close-task-form').addEventListener('click', function() {
            taskForm.classList.remove('show');
            taskForm.classList.add('hide');
            setTimeout(() => {
                taskForm.style.display = 'none';
            }, 400);
        });


        document.getElementById('submit-task-button').addEventListener('click', function() {

            // Recoge los datos del formulario
            const formData = {
                cliente_id: document.querySelector('input[name="cliente_id"]').value,
                asunto_id: document.querySelector('input[name="asunto_id"]').value,
                // Otros campos...
            };

            console.log('Datos del formulario:', formData); // Verifica que los datos están siendo recogidos

            // Realiza la solicitud con fetch
            fetch('/tareas', {
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log('Tarea creada:', data.task);
                        // updateTaskTable(data.task);
                        document.getElementById('add-task-form').reset();
                    } else {
                        console.error('Errores de validación:', data.errors);
                    }
                })
                .catch(error => console.error('Error en la solicitud:', error));
        });


        // Escuchar el canal y el evento del WebSocket
        window.Echo.channel('tasks')
            .listen('TaskCreated', (e) => {
                console.log('Nueva tarea creada:', e);
                updateTaskTable(e.task); // Actualiza la tabla cuando se crea una nueva tarea
            });
    });









    // Función para actualizar la tabla con la nueva tarea
    function updateTaskTable(task) {
        const tableBody = document.querySelector('table tbody');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${task.id}</td>
            <td>${task.asunto.nombre || 'Sin asunto'}</td>
            <td>${task.cliente.nombre_fiscal || 'Sin cliente'}</td>
            <td>${task.tipo ? task.tipo.nombre : 'Sin tipo'}</td>
            <td>${task.subtipo}</td>
            <td>${task.estado}</td>
            <td>${task.users && task.users.length > 0 ? task.users.map(user => user.name).join(', ') : 'Sin asignación'}</td>
            <td>${task.descripcion}</td>
            <td>${task.observaciones}</td>
            <td>${task.archivo || 'No disponible'}</td>
            <td>${task.facturable ? 'Sí' : 'No'}</td>
            <td>${task.facturado || 'No facturado'}</td>
            <td>${task.precio || 'N/A'}</td>
            <td>${task.suplido || 'N/A'}</td>
            <td>${task.coste || 'N/A'}</td>
            <td>${task.fecha_inicio ? new Date(task.fecha_inicio).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.fecha_vencimiento ? new Date(task.fecha_vencimiento).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.fecha_imputacion ? new Date(task.fecha_imputacion).toLocaleDateString() : 'Sin fecha'}</td>
            <td>${task.tiempo_previsto || 'N/A'}</td>
            <td>${task.tiempo_real || 'N/A'}</td>
        `;

        tableBody.appendChild(row);
    }
</script>
@endsection