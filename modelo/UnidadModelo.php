<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Conexion.php';

class UnidadModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function listar() {
        try {
            $sql = "SELECT id, descripcion FROM T_unidad ORDER BY id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function insertar($descripcion) {
        try {
            $sql = "INSERT INTO T_unidad (descripcion) VALUES (:descripcion)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':descripcion' => $descripcion]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM T_unidad WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Retorna falso si está enlazado a un producto (ON DELETE RESTRICT)
            return false;
        }
    }

       // NUEVO MÉTODO: Verifica si un nombre de producto ya está registrado
    // Si pasamos el $id_ignorar, excluirá ese ID de la búsqueda (útil para la edición)
    public function existeNombre($nombre, $id_ignorar = 0) {
        try {
            if ($id_ignorar > 0) {
                // Modo Edición: Busca si otra fila diferente ya usa este nombre
                $sql = "SELECT COUNT(*) FROM T_productos WHERE nombre_producto = :nombre AND id != :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':nombre' => $nombre, ':id' => $id_ignorar]);
            } else {
                // Modo Inserción: Busca si el nombre ya existe en cualquier registro
                $sql = "SELECT COUNT(*) FROM T_productos WHERE nombre_producto = :nombre";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':nombre' => $nombre]);
            }
            
            // Retorna true si el conteo es mayor a 0 (el nombre ya existe)
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}