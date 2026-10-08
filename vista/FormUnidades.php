<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormUnidades - Gestión de Unidades</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Inclusión del Menú Horizontal -->
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'menu.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <!-- FORMULARIO (Izquierda) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-center">Registrar Unidad de Medida</h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-dismissible fade show <?php echo ($tipoMensaje === 'exito') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?modulo=unidades" method="POST">
                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-semibold">Descripción de la Unidad</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ej. Litros, Kilogramos, Cajas" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary shadow-sm">Guardar Unidad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLA DE LISTADO (Derecha) -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 text-center">Unidades de Medida Registradas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 100px;">ID</th>
                                    <th>Descripción</th>
                                    <th class="text-center pe-3" style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($unidades)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            No hay unidades registradas todavía.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($unidades as $u): ?>
                                        <tr>
                                            <td class="ps-3 fw-bold text-secondary">#<?php echo $u['id']; ?></td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($u['descripcion']); ?></td>
                                            <td class="text-center pe-3">
                                                <a href="index.php?modulo=unidades&action=eliminar&id=<?php echo $u['id']; ?>" 
                                                   class="btn btn-danger btn-sm px-3" 
                                                   onclick="return confirmarEliminacion('<?php echo addslashes($u['descripcion']); ?>');">
                                                    Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(nombre) {
    return confirm("¿Deseas eliminar la unidad '" + nombre + "'?\n\nNota: Si está enlazada a algún producto actual, el sistema denegará la acción por seguridad.");
}
</script>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>