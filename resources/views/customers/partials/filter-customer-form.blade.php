<input type="hidden" id="user-session-id" value="{{ auth()->user()->id }}">

<!-- Formulario para filtrar clientes -->
<div id="filter-customer-form" class="customer-form hide">
    <h3 class="form-title">Filtrar Clientes</h3>
    <!-- Formulario para filtrar clientes -->
    <form method="POST" id="filter-customer-form-content" enctype="multipart/form-data">
        @csrf
        <!-- Fila 1: Nombre Fiscal, NIF, Móvil, Fijo, Email -->
        <div class="form-row">
            <div class="form-group wide">
                <label for="filter-nombre-fiscal-input">Nombre Fiscal:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-nombre-fiscal-input" name="filter_nombre_fiscal"  class="autocomplete-input" placeholder="Buscar cliente..." autocomplete="off">
                    <input type="hidden" name="filter_nombre-fiscal_id" id="filter-nombre-fiscal-id-input">
                    <ul id="filter-nombre-fiscal-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="filter-nif-input">NIF:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-nif-input" name="filter_nif"  class="autocomplete-input" placeholder="Buscar NIF..." autocomplete="off">
                    <input type="hidden" name="filter_nif_id" id="filter-nif-id-input">
                    <ul id="filter-nif-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="filter-movil-input">Móvil:</label>
                <input type="text" id="filter-movil-input" name="filter_movil" placeholder="Buscar móvil..." autocomplete="off">
            </div>

            <div class="form-group medium">
                <label for="filter-fijo-input">Fijo:</label>
                <input type="text" id="filter-fijo-input" name="filter_fijo" placeholder="Buscar fijo..." autocomplete="off">
            </div>

            <div class="form-group wide">
                <label for="filter-email-input">Email:</label>
                <input type="email" id="filter-email-input" name="filter_email" placeholder="Buscar email..." autocomplete="off">
            </div>
        </div>

        <!-- Fila 2: Dirección, Código Postal, Población, Tipo de Cliente -->
        <div class="form-row">
            <div class="form-group narrow">
                <label for="filter-user-select">Responsable:</label>
                <div class="custom-select" name="filter-user-select" tabindex="0" id="filter-user-select">
                    <div id="filter-selected-users" class="selected-users">
                        <!-- Aquí se añadirán los usuarios seleccionados para el filtro -->
                    </div>
                    <div id="filter-user-list" class="dropdown-list" style="display: none;">
                        <ul>
                            <!-- Debes cargar dinámicamente los usuarios disponibles -->
                            @foreach($usuarios as $user)
                            <li>
                                <input class="user-checkbox" type="checkbox" id="filter-user-{{ $user->id }}" value="{{ $user->id }}">
                                <label for="filter-user-{{ $user->id }}">{{ $user->name }}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="filter_users" id="filter-user-ids"> <!-- Campo oculto para los IDs de usuarios seleccionados -->
            </div>

            <div class="form-group wide">
                <label for="filter-direccion-input">Dirección:</label>
                <input type="text" id="filter-direccion-input" name="filter_direccion" placeholder="Buscar dirección..." autocomplete="off">
            </div>

            <div class="form-group narrow">
                <label for="filter-persona_contacto">Persona Contacto:</label>
                <input type="text" name="filter_persona_contacto" id="filter-persona_contacto-input" autocomplete="off" placeholder="Persona Contacto">
            </div>

            <div class="form-group medium">
                <label for="filter-codigo-postal-input">Código Postal:</label>
                <input type="text" id="filter-codigo-postal-input" name="filter_codigo_postal" placeholder="Buscar código postal..." autocomplete="off">
            </div>

            <div class="form-group medium">
                <label for="filter-poblacion-input">Población:</label>
                <input type="text" id="filter-poblacion-input" name="filter_poblacion" placeholder="Buscar población..." autocomplete="off">
            </div>

        </div>

        <!-- Fila 3: Clasificación, Tributación, Situación, Datos Bancarios -->
        <div class="form-row">
            <div class="form-group medium">
                <label for="filter-tipo-cliente-input">Tipo de Cliente:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-tipo-cliente-input" class="autocomplete-input" placeholder="Buscar tipo..." autocomplete="off">
                    <input type="hidden" name="filter_tipo_cliente_id" id="filter-tipo-cliente-id-input">
                    <ul id="filter-tipo-cliente-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="filter-clasificacion-input">Clasificación:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-clasificacion-input" class="autocomplete-input" placeholder="Buscar clasificación..." autocomplete="off">
                    <input type="hidden" name="filter_clasificacion_id" id="filter-clasificacion-id-input">
                    <ul id="filter-clasificacion-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="filter-tributacion-input">Tributación:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-tributacion-input" class="autocomplete-input" placeholder="Buscar tributación..." autocomplete="off">
                    <input type="hidden" name="filter_tributacion_id" id="filter-tributacion-id-input">
                    <ul id="filter-tributacion-list" class="autocomplete-list"></ul>
                </div>
            </div>

            <div class="form-group medium">
                <label for="filter-situacion-input">Situación:</label>
                <div class="autocomplete">
                    <input type="text" id="filter-situacion-input" class="autocomplete-input" placeholder="Buscar situación..." autocomplete="off">
                    <input type="hidden" name="filter_situacion_id" id="filter-situacion-id-input">
                    <ul id="filter-situacion-list" class="autocomplete-list"></ul>
                </div>
            </div>


        </div>

        <!-- Fila 4: Subclase, Puntaje, Código Sage -->
        <div class="form-row">
            <div class="form-group">
                <label for="filter-subclase-input">Subclase:</label>
                <input type="text" name="filter_subclase" id="filter-subclase-input" placeholder="Buscar subclase...">
            </div>

            <div class="form-group narrow">
                <label for="filter-segundo_telefono-input">Segundo Teléfono:</label>
                <input type="text" name="filter_segundo_telefono" id="filter-segundo_telefono-input" autocomplete="off" placeholder="Segundo Teléfono">
            </div>

            <div class="form-group wide">
                <label for="filter-datos-bancarios-input">Datos Bancarios:</label>
                <input type="text" id="filter-datos-bancarios-input" name="filter_datos_bancarios" placeholder="Buscar datos bancarios..." autocomplete="off">
            </div>

            <div class="form-group">
                <label for="filter-puntaje-input">Puntaje:</label>
                <input type="number" name="filter_puntaje" id="filter-puntaje-input" placeholder="Buscar puntaje...">
            </div>

            <div class="form-group">
                <label for="filter-codigo-sage-input">Código Sage:</label>
                <input type="text" name="filter_codigo_sage" id="filter-codigo-sage-input" placeholder="Buscar código Sage...">
            </div>
        </div>

        <!-- Botones del formulario -->
        <div class="form-buttons">
            <button type="button" id="apply-filter-button" class="btn-submit">Aplicar Filtros</button>
            <button type="button" id="clear-filter-button" class="btn-clear">Limpiar</button>
            <button type="button" id="cancel-filter-button" class="btn-close">Cancelar</button>
        </div>
    </form>
</div>