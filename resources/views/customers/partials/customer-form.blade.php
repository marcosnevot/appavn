<!-- Formulario de nuevo cliente en un menú desplegable -->
<div id="customer-form" class="customer-form hide">
    <h3 class="form-title">Nuevo Cliente</h3>
    <!-- Formulario para añadir clientes -->
    <form method="POST" id="add-customer-form" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Nombre Fiscal, NIF, Teléfono, Email -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="nombre_fiscal">Nombre Fiscal:</label>
                <input type="text" name="nombre_fiscal" id="nombre_fiscal" placeholder="Nombre Fiscal" autocomplete="off" required>
            </div>

            <div class="form-group narrow">
                <label for="nif">NIF:</label>
                <input type="text" name="nif" id="nif" autocomplete="off" placeholder="NIF del Cliente">
            </div>

            <div class="form-group narrow">
                <label for="movil">Móvil:</label>
                <input type="text" name="movil" id="movil" autocomplete="off" placeholder="Teléfono Móvil">
            </div>

            <div class="form-group narrow">
                <label for="fijo">Fijo:</label>
                <input type="text" name="fijo" id="fijo" autocomplete="off" placeholder="Teléfono Fijo">
            </div>

            <div class="form-group wide">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" autocomplete="off" placeholder="Email">
            </div>
        </div>

        <!-- Fila 2: Asignado a, Dirección, Código Postal, Población -->
        <div class="form-row">
            <div class="form-group narrow">
                <label for="user-select">Responsable:</label>
                <div class="custom-select" name="user-select" tabindex="0" id="user-select">
                    <div id="selected-users" class="selected-users">
                        <!-- Aquí se añadirán los usuarios seleccionados -->
                    </div>
                    <div id="user-list" class="dropdown-list" style="display: none;">
                        <ul>
                            @foreach($usuarios as $user)
                            <li>
                                <input class="user-checkbox" type="checkbox" id="user-{{ $user->id }}" value="{{ $user->id }}">
                                <label for="user-{{ $user->id }}">{{ $user->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="users" id="user-ids"> <!-- Campo oculto para los IDs de usuarios seleccionados -->
            </div>

            <div class="form-group wide">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" autocomplete="off" placeholder="Dirección del Cliente">
            </div>

            <div class="form-group narrow">
                <label for="persona_contacto">Persona Contacto:</label>
                <input type="text" name="persona_contacto" id="persona_contacto" autocomplete="off" placeholder="Persona Contacto">
            </div>

            <div class="form-group narrow">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" name="codigo_postal" id="codigo_postal" autocomplete="off" placeholder="Código Postal">
            </div>

            <div class="form-group narrow">
                <label for="poblacion">Población:</label>
                <input type="text" name="poblacion" id="poblacion" autocomplete="off" placeholder="Población">
            </div>
        </div>

        <!-- Fila 3: Tipo de Cliente, Clasificación, Tributación, Situación -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="tipo_cliente_id">Tipo de Cliente:</label>
                <div class="autocomplete">
                    <input type="text" id="tipo_cliente-input" class="autocomplete-input" placeholder="Buscar o crear clasificación..." autocomplete="off" >
                    <input type="hidden" name="tipo_cliente_id" id="tipo_cliente-id-input"> <!-- Campo oculto para el id del tipo_cliente -->
                    <ul id="tipo_cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="clasificacion_id">Clasificación:</label>
                <div class="autocomplete">
                    <input type="text" id="clasificacion-input" class="autocomplete-input" placeholder="Buscar o crear tipo..." autocomplete="off" >
                    <input type="hidden" name="clasificacion_id" id="clasificacion-id-input"> <!-- Campo oculto para el id del tipo -->
                    <ul id="clasificacion-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="tributacion_id">Tributación:</label>
                <div class="autocomplete">
                    <input type="text" id="tributacion-input" class="autocomplete-input" placeholder="Buscar o crear tributación..." autocomplete="off" >
                    <input type="hidden" name="tributacion_id" id="tributacion-id-input"> <!-- Campo oculto para el id de la tributacion -->
                    <ul id="tributacion-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group wide">
                <label for="situacion_id">Situación:</label>
                <div class="autocomplete">
                    <input type="text" id="situacion-input" class="autocomplete-input" placeholder="Buscar o crear situación..." autocomplete="off" >
                    <input type="hidden" name="situacion_id" id="situacion-id-input"> <!-- Campo oculto para el id de la tributacion -->
                    <ul id="situacion-list" class="autocomplete-list"></ul>
                </div>
            </div>
        </div>

        <!-- Fila 4: Datos Bancarios, Puntaje, Código SAGE -->
        <div class="form-row">
            <div class="form-group narrow">
                <label for="subclase">Subclase:</label>
                <input type="text" name="subclase" id="subclase" placeholder="Subclase">
            </div>

            <div class="form-group narrow">
                <label for="segundo_telefono">Segundo Teléfono:</label>
                <input type="text" name="segundo_telefono" id="segundo_telefono" autocomplete="off" placeholder="Segundo Teléfono">
            </div>

            <div class="form-group wide">
                <label for="datos_bancarios">Datos Bancarios:</label>
                <textarea name="datos_bancarios" id="datos_bancarios" rows="2" placeholder="Datos Bancarios del Cliente"></textarea>
            </div>

            <div class="form-group narrow">
                <label for="puntaje">Puntaje:</label>
                <input type="number" name="puntaje" id="puntaje" step="1" placeholder="Puntaje">
            </div>

            <div class="form-group narrow">
                <label for="codigo_sage">Código SAGE:</label>
                <input type="number" name="codigo_sage" id="codigo_sage" step="1" placeholder="Código SAGE">
            </div>
        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit">Añadir Cliente</button>
            <button type="button" id="close-customer-form" class="btn-close">Cerrar</button>
        </div>
    </form>
</div>

<!-- Modal de confirmación para crear un nuevo tipo de cliente, clasificación, tributación o situación -->
<div id="confirm-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <!-- Mensajes de confirmación para tipo de cliente, clasificación, tributación o situación -->
        <p id="modal-message" class="modal-message">
            <!-- Aquí se mostrarán los mensajes de confirmación específicos -->
            <span id="modal-clasificacion-message"></span><br>
            <span id="modal-tipo-cliente-message"></span><br>
            <span id="modal-tributacion-message"></span><br>
            <span id="modal-situacion-message"></span>
        </p>
        <div class="modal-actions">
            <button id="confirm-modal-yes" class="btn btn-confirm">Sí, crear</button>
            <button id="confirm-modal-no" class="btn btn-cancel">No, cancelar</button>
        </div>
    </div>
</div>