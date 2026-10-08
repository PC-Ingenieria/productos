<?php $modulo_actual = isset($_GET['modulo']) ? $_GET['modulo'] : 'productos'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">📦 Sistema POS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold <?php echo ($modulo_actual === 'productos') ? 'active' : 'text-white-50'; ?>" href="index.php?modulo=productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold <?php echo ($modulo_actual === 'unidades') ? 'active' : 'text-white-50'; ?>" href="index.php?modulo=unidades">Unidades</a>
                </li>
                
                <!-- 📊 MENÚ DESPLEGABLE DE REPORTES (MODIFICADO) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 fw-semibold <?php echo (strpos($modulo_actual, 'reporte_') === 0) ? 'active' : 'text-white-50'; ?>" 
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Reportes
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary shadow" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item text-white py-2 <?php echo ($modulo_actual === 'reporte_productos') ? 'bg-primary' : ''; ?>" href="index.php?modulo=reporte_productos">
                                📋 Reporte de Productos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-white py-2 <?php echo ($modulo_actual === 'reporte_unidades') ? 'bg-primary' : ''; ?>" href="index.php?modulo=reporte_unidades">
                                📐 Reporte de Unidades
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link px-3 text-white-50" href="#ayuda">Ayuda</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Estilo extra para simular el efecto hover y diseño en el menú desplegable -->
<style>
    .dropdown-item:hover { background-color: #343a40 !important; color: #0d6efd !important; }
</style>