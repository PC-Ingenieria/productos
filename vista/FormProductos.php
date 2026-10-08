<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormProductos - Inventario Completo</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Inclusión del Menú Horizontal -->
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'menu.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <!-- SECCIÓN 1: El Formulario (Dinamizado para Insertar/Editar) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header <?php echo $productoEditar ? 'bg-warning text-dark' : 'bg-primary text-white'; ?> py-3">
                    <h5 class="mb-0 text-center">
                        <?php echo $productoEditar ? '⚠️ Modificar Producto' : 'Registrar Producto'; ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-dismissible fade show <?php echo ($tipoMensaje === 'exito') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php" method="POST">
                        <!-- Campo oculto para pasar el ID si estamos editando -->
                        <?php if ($productoEditar): ?>
                            <input type="hidden" name="id_producto" value="<?php echo $productoEditar['id']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nombre_producto" class="form-label fw-semibold">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" 
                                   value="<?php echo $productoEditar ? htmlspecialchars($productoEditar['nombre_producto']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label fw-semibold">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">Q</span>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" 
                                       value="<?php echo $productoEditar ? $productoEditar['precio'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cantidad" class="form-label fw-semibold">Cantidad (Stock)</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" 
                                   value="<?php echo $productoEditar ? $productoEditar['cantidad'] : ''; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="unidad" class="form-label fw-semibold">Unidad de Medida</label>
                            <select class="form-select" id="unidad" name="unidad" required>
                                <option value="" disabled <?php echo !$productoEditar ? 'selected' : ''; ?>>-- Seleccione --</option>
                                <?php foreach ($unidades as $u): ?>
                                    <option value="<?php echo $u['id']; ?>" 
                                        <?php echo ($productoEditar && $productoEditar['unidad'] == $u['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($u['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn <?php echo $productoEditar ? 'btn-warning text-dark' : 'btn-primary'; ?> shadow-sm fw-bold">
                                <?php echo $productoEditar ? 'Actualizar Producto' : 'Guardar Producto'; ?>
                            </button>
                            <?php if ($productoEditar): ?>
                                <a href="index.php" class="btn btn-secondary btn-sm">Cancelar Edición</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: La Tabla de Productos -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 text-center">Listado de Inventario</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 70px;">ID</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Medida</th>
                                    <th class="text-center pe-3" style="width: 180px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($productos)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No hay productos registrados todavía.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($productos as $p): ?>
                                        <tr class="<?php echo ($productoEditar && $productoEditar['id'] == $p['id']) ? 'table-warning' : ''; ?>">
                                            <td class="ps-3 fw-bold text-secondary">#<?php echo $p['id']; ?></td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($p['nombre_producto']); ?></td>
                                            <td>$<?php echo number_format($p['precio'], 2); ?></td>
                                            <td>
                                                <span class="badge <?php echo ($p['cantidad'] > 0) ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo $p['cantidad']; ?>
                                                </span>
                                            </td>
                                            <td><span class="text-muted"><?php echo htmlspecialchars($p['unidad_medida']); ?></span></td>
                                            <td class="text-center pe-3">
                                                <div class="btn-group" role="group">
                                                    <!-- BOTÓN MODIFICAR (NUEVO) -->
                                                    <a href="index.php?action=editar&id=<?php echo $p['id']; ?>" 
                                                       class="btn btn-warning btn-sm fw-semibold">
                                                        Modificar
                                                    </a>
                                                    <a href="index.php?action=eliminar&id=<?php echo $p['id']; ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirmarEliminacion('<?php echo addslashes($p['nombre_producto']); ?>');">
                                                        Eliminar
                                                    </a>
                                                </div>
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
    return confirm("¿Estás seguro de que deseas eliminar el producto '" + nombre + "'? Esta acción no se puede deshacer.");
}
</script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>