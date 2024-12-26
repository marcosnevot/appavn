<div class="periodic-container">
    <!-- Título de la Sección -->
    <div class="users-header">
        <p class="section-description">Gestiona y visualiza los asuntos y tipos de tareas y las clasificaciones, situaciones, tributaciones y tipos de clientes registrados en la base de datos.</p>
    </div>

    <!-- Contenido Principal -->
    <div class="lists-container">
        <!-- Lista de Asuntos -->
        <div class="data-list" role="region" aria-labelledby="asuntos-title">
            <div class="list-header">
                <h4 id="asuntos-title">Asuntos</h4>
                <input type="text" id="search-asuntos" class="list-search" aria-label="Buscar en asuntos">
                <button class="btn-refresh" id="refresh-asuntos" aria-label="Refrescar lista de asuntos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.929 19.071a10 10 0 1114.142 0M12 5v7l4 2" />
                    </svg>
                </button>
            </div>
            <ul class="data-items" id="asuntos-list" aria-labelledby="asuntos-title" role="list">
                <li class="data-item placeholder" role="status">Cargando asuntos...</li>
            </ul>
        </div>

        <!-- Lista de Tipos -->
        <div class="data-list" role="region" aria-labelledby="tipos-title">
            <div class="list-header">
                <h4 id="tipos-title">Tipos de Tarea</h4>
                <input type="text" id="search-tipos" class="list-search" aria-label="Buscar en tipos">
                <button class="btn-refresh" id="refresh-tipos" aria-label="Refrescar lista de tipos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.929 19.071a10 10 0 1114.142 0M12 5v7l4 2" />
                    </svg>
                </button>
            </div>
            <ul class="data-items" id="tipos-list" aria-labelledby="tipos-title" role="list">
                <li class="data-item placeholder" role="status">Cargando tipos...</li>
            </ul>
        </div>

    </div>
    <div class="lists-container">


        <!-- Lista de Clasificaciones -->
        <div class="data-list" role="region" aria-labelledby="clasificaciones-title">
            <div class="list-header">
                <h4 id="clasificaciones-title">Clasificaciones</h4>
                <input type="text" id="search-clasificaciones" class="list-search" aria-label="Buscar en clasificaciones">
                <button class="btn-refresh" id="refresh-clasificaciones" aria-label="Refrescar lista de clasificaciones">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.929 19.071a10 10 0 1114.142 0M12 5v7l4 2" />
                    </svg>
                </button>
            </div>
            <ul class="data-items" id="clasificaciones-list" aria-labelledby="clasificaciones-title" role="list">
                <li class="data-item placeholder" role="status">Cargando clasificaciones...</li>
            </ul>
        </div>

        <!-- Lista de Situaciones -->
        <div class="data-list" role="region" aria-labelledby="situaciones-title">
            <div class="list-header">
                <h4 id="situaciones-title">Situaciones</h4>
                <input type="text" id="search-situaciones" class="list-search" aria-label="Buscar en situaciones">
                <button class="btn-refresh" id="refresh-situaciones" aria-label="Refrescar lista de situaciones">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.929 19.071a10 10 0 1114.142 0M12 5v7l4 2" />
                    </svg>
                </button>
            </div>
            <ul class="data-items" id="situaciones-list" aria-labelledby="situaciones-title" role="list">
                <li class="data-item placeholder" role="status">Cargando situaciones...</li>
            </ul>
        </div>

        <!-- Lista de Tributaciones -->
        <div class="data-list" role="region" aria-labelledby="tributaciones-title">
            <div class="list-header">
                <h4 id="tributaciones-title">Tributaciones</h4>
                <input type="text" id="search-tributaciones" class="list-search" aria-label="Buscar en tributaciones">
                <button class="btn-refresh" id="refresh-tributaciones" aria-label="Refrescar lista de tributaciones">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.929 19.071a10 10 0 1114.142 0M12 5v7l4 2" />
                    </svg>
                </button>
            </div>
            <ul class="data-items" id="tributaciones-list" aria-labelledby="tributaciones-title" role="list">
                <li class="data-item placeholder" role="status">Cargando Tributaciones...</li>
            </ul>
        </div>

        <!-- Lista de Tipos Cliente -->
        <div class="data-list" role="region" aria-labelledby="tiposcliente-title">
            <div class="list-header">
                <h4 id="tiposcliente-title">Tipos de Cliente</h4>
                <input type="text" id="search-tiposcliente" class="list-search" aria-label="Buscar en tipos de cliente">
                <button class="btn-refresh" id="refresh-tiposcliente" aria-label="Refrescar lista de tipos de cliente">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.929 19.071a10 10 0 1114.142 0M12 5v7l4 2" />
                    </svg>
                </button>
            </div>
            <ul class="data-items" id="tiposcliente-list" aria-labelledby="tiposcliente-title" role="list">
                <li class="data-item placeholder" role="status">Cargando tipos de cliente...</li>
            </ul>
        </div>
    </div>
</div>