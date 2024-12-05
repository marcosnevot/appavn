<!-- Formulario de edición de cliente -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="user-id" content="{{ auth()->user()->id }}">

<div id="edit-customer-form-container" class="customer-form hide">
    <h3 class="form-title">Editar Cliente</h3>

    <!-- Formulario para editar el cliente -->
    <form method="POST" id="edit-customer-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="customer_id" id="customer_id" value="">
        <input type="hidden" name="_method" value="PUT"> <!-- Asegúrate de incluir este campo -->

        <!-- Fila 1: Nombre Fiscal, NIF -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="nombre_fiscal">Nombre Fiscal:</label>
                <input type="text" name="nombre_fiscalEdit" id="nombre_fiscal" autocomplete="off" required>
            </div>

            <div class="form-group medium">
                <label for="nif">NIF:</label>
                <input type="text" name="nifEdit" id="nif" autocomplete="off">
            </div>

            <div class="form-group narrow">
                <label for="movil">Teléfono Móvil:</label>
                <input type="text" name="movilEdit" id="movil" autocomplete="off">
            </div>

            <div class="form-group narrow">
                <label for="fijo">Teléfono Fijo:</label>
                <input type="text" name="fijoEdit" id="fijo" autocomplete="off">
            </div>

            <div class="form-group wide">
                <label for="email">Email:</label>
                <input type="email" name="emailEdit" id="email" autocomplete="off">
            </div>
        </div>

        <!-- Fila 2: Responsable, dirección, población, código postal -->
        <div class="form-row">
            <div class="form-group narrow">
                <label for="user-select-edit">Responsable:</label>
                <div class="custom-select" name="user-select-edit" tabindex="0" id="user-select-edit">
                    <div id="selected-users-edit" class="selected-users">
                        <!-- Aquí se añadirán los usuarios seleccionados para la edición -->
                    </div>
                    <div id="user-list-edit" class="dropdown-list" style="display: none;">
                        <ul>
                            @foreach($usuarios as $user)
                            <li>
                                <input class="user-checkbox" type="checkbox" id="user-edit-{{ $user->id }}" name="usersEdit[]" value="{{ $user->id }}">
                                <label for="user-edit-{{ $user->id }}">{{ $user->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="usersEdit" id="user-ids-edit">
            </div>

            <div class="form-group wide">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccionEdit" id="direccion" autocomplete="off">
            </div>

            <div class="form-group narrow">
                <label for="persona_contacto">Persona Contacto:</label>
                <input type="text" name="persona_contactoEdit" id="persona_contacto" autocomplete="off" placeholder="Persona Contacto">
            </div>

            <div class="form-group medium">
                <label for="poblacion">Población:</label>
                <input type="text" name="poblacionEdit" id="poblacion">
            </div>

            <div class="form-group narrow">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" name="codigo_postalEdit" id="codigo_postal">
            </div>
        </div>



        <!-- Fila 3: tipo, clasificación, Tributación, Situación  -->
        <div class="form-row">

            <div class="form-group medium">
                <label for="tipo_cliente">Tipo de Cliente:</label>
                <select name="tipo_clienteEdit" id="tipo_cliente">
                    <option value="">Sin Tipo de Cliente</option> <!-- Opción vacía -->
                    @foreach($tiposClientes as $tipo)
                    <option value="{{ $tipo->id }}" {{ isset($customer) && $customer->tipo_cliente_id == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group medium">
                <label for="clasificacion">Clasificación:</label>
                <select name="clasificacionEdit" id="clasificacion">
                    <option value="">Sin Clasificación</option> <!-- Opción vacía -->
                    @foreach($clasificaciones as $clasificacion)
                    <option value="{{ $clasificacion->id }}" {{ isset($customer) && $customer->clasificacion_id == $clasificacion->id ? 'selected' : '' }}>
                        {{ $clasificacion->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group medium">
                <label for="tributacion">Tributación:</label>
                <select name="tributacionEdit" id="tributacion">
                    <option value="">Sin Tributación</option> <!-- Opción vacía -->
                    @foreach($tributaciones as $tributacion)
                    <option value="{{ $tributacion->id }}" {{ isset($customer) && $customer->tributacion_id == $tributacion->id ? 'selected' : '' }}>
                        {{ $tributacion->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group medium">
                <label for="situacion">Situación:</label>
                <select name="situacionEdit" id="situacion">
                    <option value="">Sin Situación</option> <!-- Opción vacía -->
                    @foreach($situaciones as $situacion)
                    <option value="{{ $situacion->id }}" {{ isset($customer) && $customer->situacion_id == $situacion->id ? 'selected' : '' }}>
                        {{ $situacion->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- Fila 5: Datos Bancarios, Responsable -->
        <div class="form-row">

            <div class="form-group wide">
                <label for="subclase">Subclase:</label>
                <input type="text" name="subclaseEdit" id="subclase" placeholder="Subclase del Cliente">
            </div>

            <div class="form-group narrow">
                <label for="segundo_telefono">Segundo Teléfono:</label>
                <input type="text" name="segundo_telefonoEdit" id="segundo_telefono" autocomplete="off" placeholder="Segundo Teléfono">
            </div>

            <div class="form-group wide">
                <label for="datos_bancarios">Datos Bancarios:</label>
                <textarea name="datos_bancariosEdit" id="datos_bancarios" rows="2" autocomplete="off"></textarea>
            </div>


            <div class="form-group narrow">
                <label for="puntaje">Puntaje:</label>
                <input type="number" step="1" name="puntajeEdit" id="puntaje" autocomplete="off">
            </div>

            <div class="form-group narrow">
                <label for="codigo_sage">Código SAGE:</label>
                <input type="number" name="codigo_sageEdit" id="codigo_sage" step="1" placeholder="Código SAGE del Cliente" autocomplete="off">
            </div>
        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit" id="btn-edit-customer-form">Guardar Cambios</button>
            <button type="button" id="close-edit-customer-form" class="btn-close">Cerrar</button>
        </div>
    </form>
</div>