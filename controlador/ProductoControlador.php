<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'modelo' . DIRECTORY_SEPARATOR . 'ProductoModelo.php';

class ProductoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ProductoModelo();
    }

    public function gestionarRegistro() {
        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
        $tipoMensaje = isset($_SESSION['tipoMensaje']) ? $_SESSION['tipoMensaje'] : "";
        unset($_SESSION['mensaje'], $_SESSION['tipoMensaje']);

        $productoEditar = null;

        // ---- ACCIÓN 1: PROCESAR ELIMINACIÓN ----
        if (isset($_GET['action']) && $_GET['action'] === 'eliminar' && isset($_GET['id'])) {
            $idEliminar = intval($_GET['id']);
            if ($idEliminar > 0) {
                $resultado = $this->modelo->eliminar($idEliminar);
                if ($resultado) {
                    $_SESSION['mensaje'] = "¡Producto eliminado exitosamente!";
                    $_SESSION['tipoMensaje'] = "exito";
                } else {
                    $_SESSION['mensaje'] = "Error: No se pudo eliminar el producto.";
                    $_SESSION['tipoMensaje'] = "error";
                }
                header("Location: index.php");
                exit();
            }
        }

        // ---- ACCIÓN 2: DETECTAR MODO EDICIÓN (GET) ----
        if (isset($_GET['action']) && $_GET['action'] === 'editar' && isset($_GET['id'])) {
            $idEditar = intval($_GET['id']);
            if ($idEditar > 0) {
                $productoEditar = $this->modelo->obtenerPorId($idEditar);
            }
        }

        // ---- ACCIÓN 3: PROCESAR FORMULARIO (POST) ----
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id              = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
            $nombre          = trim($_POST['nombre_producto']);
            $precio          = floatval($_POST['precio']);
            $cantidad        = intval($_POST['cantidad']);
            $unidad          = intval($_POST['unidad']);

            if (!empty($nombre) && $precio > 0 && $cantidad >= 0 && $unidad > 0) {
                
                // 💡 VALIDACIÓN DE REPETIDOS (NUEVO)
                // Le pasamos el $id actual; si es 0 (inserción) validará globalmente, si es > 0 (edición) omitirá el producto actual
                if ($this->modelo->existeNombre($nombre, $id)) {
                    $_SESSION['mensaje'] = "Error: Ya existe un producto registrado con el nombre '{$nombre}'.";
                    $_SESSION['tipoMensaje'] = "error";
                    
                    // Si estábamos editando, regresamos a la pantalla de edición para no perder el flujo
                    if ($id > 0) {
                        header("Location: index.php?action=editar&id={$id}");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                }
                
                // Si pasa la validación de duplicados, procedemos de forma normal:
                if ($id > 0) {
                    $resultado = $this->modelo->actualizar($id, $nombre, $precio, $cantidad, $unidad);
                    $accion_texto = "actualizado";
                } else {
                    $resultado = $this->modelo->insertar($nombre, $precio, $cantidad, $unidad);
                    $accion_texto = "registrado";
                }
                
                if ($resultado) {
                    $_SESSION['mensaje'] = "¡Producto {$accion_texto} exitosamente!";
                    $_SESSION['tipoMensaje'] = "exito";
                } else {
                    $_SESSION['mensaje'] = "Error: No se pudo procesar el producto.";
                    $_SESSION['tipoMensaje'] = "error";
                }
            } else {
                $_SESSION['mensaje'] = "Por favor, completa todos los campos con valores válidos.";
                $_SESSION['tipoMensaje'] = "error";
            }

            header("Location: index.php");
            exit();
        }

        // Obtener datos para la vista
        $unidades = $this->modelo->obtenerUnidades();
        if (!$unidades) { $unidades = []; }

        $productos = $this->modelo->listarProductosConUnidad();
        if (!$productos) { $productos = []; }

        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vista' . DIRECTORY_SEPARATOR . 'FormProductos.php';
    }
}