<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'modelo' . DIRECTORY_SEPARATOR . 'UnidadModelo.php';

class UnidadControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new UnidadModelo();
    }

    public function gestionarUnidades() {
        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
        $tipoMensaje = isset($_SESSION['tipoMensaje']) ? $_SESSION['tipoMensaje'] : "";
        unset($_SESSION['mensaje'], $_SESSION['tipoMensaje']);

        // ---- ACCIÓN 1: ELIMINAR UNIDAD ----
        if (isset($_GET['action']) && $_GET['action'] === 'eliminar' && isset($_GET['id'])) {
            $idEliminar = intval($_GET['id']);
            if ($idEliminar > 0) {
                $resultado = $this->modelo->eliminar($idEliminar);
                if ($resultado) {
                    $_SESSION['mensaje'] = "¡Unidad de medida eliminada!";
                    $_SESSION['tipoMensaje'] = "exito";
                } else {
                    $_SESSION['mensaje'] = "No se puede eliminar: Esta unidad está asignada a uno o más productos.";
                    $_SESSION['tipoMensaje'] = "error";
                }
                header("Location: index.php?modulo=unidades");
                exit();
            }
        }

        // ---- ACCIÓN 2: GUARDAR UNIDAD (POST) ----
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = trim($_POST['descripcion']);

            if (!empty($descripcion)) {
                $resultado = $this->modelo->insertar($descripcion);
                if ($resultado) {
                    $_SESSION['mensaje'] = "¡Unidad registrada exitosamente!";
                    $_SESSION['tipoMensaje'] = "exito";
                } else {
                    $_SESSION['mensaje'] = "Error al intentar guardar la unidad.";
                    $_SESSION['tipoMensaje'] = "error";
                }
            } else {
                $_SESSION['mensaje'] = "El campo descripción no puede estar vacío.";
                $_SESSION['tipoMensaje'] = "error";
            }
            header("Location: index.php?modulo=unidades");
            exit();
        }

        // Obtener la lista para la tabla
        $unidades = $this->modelo->listar();

        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vista' . DIRECTORY_SEPARATOR . 'FormUnidades.php';
    }
}