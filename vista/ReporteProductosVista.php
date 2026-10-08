<div class="container my-5">
    <div class="card shadow-sm border-0">
        <!-- Encabezado de la Tarjeta (Se oculta al imprimir) -->
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3 d-print-none">
            <h5 class="mb-0">📊 Reporte General de Inventario</h5>
            <!-- Botón para disparar la generación del PDF -->
            <button class="btn btn-danger fw-semibold shadow-sm" onclick="generarPDF()">
                📄 Descargar Reporte PDF
            </button>
            <a href="index.php?modulo=descargar_pdf" class="btn btn-danger fw-semibold shadow-sm">
                📄 Descargar Reporte PDF Directo
            </a>
        </div>

        <div class="card-body p-4">
            <!-- Encabezado del documento real (Solo se ve en el PDF impreso) -->
            <div class="d-none d-print-block border-bottom pb-3 mb-4 text-center">
                <h2 class="fw-bold text-primary mb-1">SISTEMA POS - INVENTARIO GENERAL</h2>
                <p class="text-muted mb-0">Reporte oficial generado el: <?php echo date('d/m/Y H:i'); ?></p>
            </div>

            <!-- Tabla de datos -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Nombre del Producto</th>
                            <th>Precio Unitario</th>
                            <th>Stock Disponible</th>
                            <th>Unidad de Medida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No hay productos registrados en el inventario.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?php echo $p['id']; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($p['nombre_producto']); ?></td>
                                    <td>$<?php echo number_format($p['precio'], 2); ?></td>
                                    <td>
                                        <span class="fw-bold <?php echo ($p['cantidad'] > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo $p['cantidad']; ?>
                                        </span>
                                    </td>
                                    <td><span class="text-muted"><?php echo htmlspecialchars($p['unidad_medida']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 💡 ESTILOS CSS AVANZADOS PARA IMPRESIÓN Y SCRIPT DE DETONACIÓN -->
<style>
@media print {
    /* Ocultamos el menú de navegación completo y las tarjetas interactivas de Bootstrap */
    .navbar, .d-print-none, button {
        display: none !important;
    }
    /* Quitamos márgenes grises por defecto del navegador para aprovechar la hoja A4 */
    body {
        background-color: #ffffff !important;
        margin: 0;
        padding: 0;
    }
    .card {
        box-shadow: none !important;
        border: none !important;
    }
}
</style>

<script>
function generarPDF() {
    // Abre automáticamente el cuadro de diálogo de impresión del sistema operativo
    // El usuario solo debe elegir "Guardar como PDF" de su lista de impresoras
    window.print();
}
</script>