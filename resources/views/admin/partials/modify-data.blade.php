<div class="modify-data-container">
    <!-- Título de la Sección -->
    <div class="section-header">
        <p class="section-description">Gestiona y visualiza los asuntos y tipos de tareas registrados en la base de datos.</p>
    </div>

    <!-- Contenido Principal -->
    <div class="lists-container">
        <!-- Lista de Asuntos -->
        <div class="data-list" role="region" aria-labelledby="asuntos-title">
            <div class="list-header">
                <h4 id="asuntos-title">Lista de Asuntos</h4>
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
                <h4 id="tipos-title">Lista de Tipos</h4>
                <input type="text" id="search-tipos" class="list-search"  aria-label="Buscar en tipos">
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


</div>