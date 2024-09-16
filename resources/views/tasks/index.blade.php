@extends('layouts.app')

@section('content')
<div class="header-tareas">
    <h2 class="title">Tareas</h2>
    <button id="new-task-button" class="btn-new-task">Nueva Tarea</button>
</div>


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

<!-- Pasamos los datos de clientes como un atributo data -->
<div id="clientes-data" data-clientes='@json($clientes)'></div>


<!-- Formulario de nueva tarea en un menú desplegable -->
<div id="task-form" class="task-form hide">
    <h3 class="form-title">Nueva Tarea</h3>
    <!-- Formulario para añadir tareas -->
    <form method="POST" id="add-task-form" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Cliente, Asunto, Tipo, Subtipo, Estado -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="cliente_id">Cliente:</label>
                <div class="autocomplete">
                    <input type="text" id="cliente-input" class="autocomplete-input" placeholder="Buscar cliente...">
                    <input type="hidden" name="cliente_id" id="cliente-id-input"> <!-- Campo oculto para el id del cliente -->
                    <ul id="cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="asunto_id">Asunto:</label>
                <select name="asunto_id" id="asunto_id" required>
                    @foreach($asuntos as $asunto)
                    <option value="{{ $asunto->id }}">{{ $asunto->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group medium">
                <label for="tipo_id">Tipo de Tarea:</label>
                <select name="tipo_id" id="tipo_id">
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group medium">
                <label for="subtipo">Subtipo:</label>
                <select name="subtipo" id="subtipo">
                    <option value="ORDINARIA">Ordinaria</option>
                    <option value="EXTRAORDINARIA">Extraordinaria</option>
                </select>
            </div>

            <div class="form-group narrow">
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
            <div class="form-group narrow">
                <label for="users">Asignado a:</label>
                <select name="users[]" id="users" multiple>
                    @foreach($usuarios as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group narrow">
                <label for="archivo">Archivo:</label>
                <input type="text" name="archivo" id="archivo">
            </div>

            <div class="form-group wide">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" rows="2"></textarea>
            </div>

            <div class="form-group wide">
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
                <input type="number" step="0.25" name="tiempo_previsto" id="tiempo_previsto">
            </div>

            <div class="form-group">
                <label for="tiempo_real">Tiempo Real (Horas):</label>
                <input type="number" step="0.25" name="tiempo_real" id="tiempo_real">
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
        const addTaskForm = document.getElementById('add-task-form'); // El propio formulario

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
            closeTaskForm();
        });

        // Ocultar el formulario cuando se hace clic fuera de él
        document.addEventListener('click', function(event) {
            // Verifica si el clic no es dentro del formulario y tampoco en el botón para abrirlo
            if (!taskForm.contains(event.target) && !document.getElementById('new-task-button').contains(event.target)) {
                if (taskForm.classList.contains('show')) {
                    closeTaskForm();
                }
            }
        });

        // Función para cerrar el formulario
        function closeTaskForm() {
            taskForm.classList.remove('show');
            taskForm.classList.add('hide');
            setTimeout(() => {
                taskForm.style.display = 'none';
            }, 400);
        }



        // Manejar el evento de envío del formulario
        addTaskForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el comportamiento predeterminado de recargar la página

            // Recoger los datos del formulario
            const formData = {
                cliente_id: document.querySelector('input[name="cliente_id"]').value,
                asunto_id: document.querySelector('select[name="asunto_id"]').value,
                tipo_id: document.querySelector('select[name="tipo_id"]').value,
                subtipo: document.querySelector('select[name="subtipo"]').value,
                estado: document.querySelector('select[name="estado"]').value,

                users: Array.from(document.querySelectorAll('select[name="users[]"] option:checked')).map(option => option.value),

                archivo: document.querySelector('input[name="archivo"]').value,
                descripcion: document.querySelector('textarea[name="descripcion"]').value,
                observaciones: document.querySelector('textarea[name="observaciones"]').value,

                facturable: document.querySelector('input[name="facturable"]').checked ? 1 : 0,
                facturado: document.querySelector('input[name="facturado"]').value,
                precio: document.querySelector('input[name="precio"]').value,
                suplido: document.querySelector('input[name="suplido"]').value,
                coste: document.querySelector('input[name="coste"]').value,

                fecha_inicio: document.querySelector('input[name="fecha_inicio"]').value,
                fecha_vencimiento: document.querySelector('input[name="fecha_vencimiento"]').value,
                fecha_imputacion: document.querySelector('input[name="fecha_imputacion"]').value,
                tiempo_previsto: document.querySelector('input[name="tiempo_previsto"]').value,
                tiempo_real: document.querySelector('input[name="tiempo_real"]').value
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
                        // updateTaskTable(data.task); // Actualiza la tabla
                        document.getElementById('add-task-form').reset(); // Resetea el formulario
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

        // Buscador de clientes

        // Obtener los datos de clientes desde el atributo data-clientes
        const clientesData = document.getElementById('clientes-data');
        const clientes = JSON.parse(clientesData.getAttribute('data-clientes'));

        const input = document.getElementById('cliente-input');
        const clienteIdInput = document.getElementById('cliente-id-input'); // Campo oculto para el ID del cliente
        const clienteList = document.getElementById('cliente-list');
        let selectedIndex = -1;

        // Función para mostrar la lista filtrada
        function filterClientes(query) {
            const filtered = clientes.filter(cliente =>
                cliente.nombre_fiscal.toLowerCase().includes(query.toLowerCase())
            );
            renderList(filtered);
        }

        // Función para renderizar la lista
        function renderList(filtered) {
            clienteList.innerHTML = '';
            if (filtered.length === 0) {
                clienteList.style.display = 'none';
                return;
            }
            clienteList.style.display = 'block';
            filtered.forEach((cliente, index) => {
                const li = document.createElement('li');
                li.textContent = cliente.nombre_fiscal;
                li.setAttribute('data-id', cliente.id);
                li.classList.add('autocomplete-item');
                if (index === selectedIndex) {
                    li.classList.add('active');
                }
                li.addEventListener('click', () => selectCliente(cliente));
                clienteList.appendChild(li);
            });
        }

        // Función para seleccionar un cliente y autocompletar el input
        function selectCliente(cliente) {
            input.value = cliente.nombre_fiscal;
            clienteIdInput.value = cliente.id; // Almacena el id en el campo oculto
            clienteList.style.display = 'none';
        }

        // Mostrar lista completa de clientes al hacer clic en el campo
        input.addEventListener('focus', function() {
            if (input.value === '') {
                renderList(clientes); // Muestra la lista completa si no se ha escrito nada
            }
        });

        // Manejador del evento input para filtrar clientes
        input.addEventListener('input', function() {
            selectedIndex = -1;
            filterClientes(this.value);
            clienteIdInput.value = '';
        });

        // Manejador para la navegación por teclado
        input.addEventListener('keydown', function(e) {
            const items = document.querySelectorAll('.autocomplete-item');
            if (e.key === 'ArrowDown') {
                selectedIndex = (selectedIndex + 1) % items.length;
                updateActiveItem(items);
            } else if (e.key === 'ArrowUp') {
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                updateActiveItem(items);
            } else if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el comportamiento por defecto de 'Enter'
                if (selectedIndex >= 0 && selectedIndex < items.length) {
                    const selectedCliente = clientes.find(cliente =>
                        cliente.nombre_fiscal === items[selectedIndex].textContent
                    );
                    selectCliente(selectedCliente);
                }
            }
        });

        // Función para actualizar el ítem activo en la lista
        function updateActiveItem(items) {
            items.forEach(item => item.classList.remove('active'));
            if (items[selectedIndex]) {
                items[selectedIndex].classList.add('active');
            }
        }

        // Cerrar la lista si se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.autocomplete')) {
                clienteList.style.display = 'none';
            }
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