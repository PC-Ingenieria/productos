<?php
// Buscamos la conexión partiendo de forma absoluta desde la raíz
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Conexion.php';

class ProductoModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function insertar($nombre, $precio, $cantidad, $unidad) {
        try {
            $sql = "INSERT INTO T_productos (nombre_producto, precio, quantity, unidad) 
                    VALUES (:nombre, :precio, :cantidad, :unidad)";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':nombre'   => $nombre,
                ':precio'   => $precio,
                ':cantidad' => $cantidad,
                ':unidad'   => $unidad
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerUnidades() {
        try {
            $sql = "SELECT id, descripcion FROM T_unidad";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listarProductosConUnidad() {
        try {
            $sql = "SELECT p.id, p.nombre_producto, p.precio, p.cantidad, u.descripcion AS unidad_medida 
                    FROM T_productos p
                    INNER JOIN T_unidad u ON p.unidad = u.id
                    ORDER BY p.id DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM T_productos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT id, nombre_producto, precio, cantidad, unidad FROM T_productos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar($id, $nombre, $precio, $cantidad, $unidad) {
        try {
            $sql = "UPDATE T_productos 
                    SET nombre_producto = :nombre, precio = :precio, cantidad = :cantidad, unidad = :unidad 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id'       => $id,
                ':nombre'   => $nombre,
                ':precio'   => $precio,
                ':cantidad' => $cantidad,
                ':unidad'   => $unidad
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 💡 LA FUNCIÓN QUE FALTABA O ESTABA MAL COPIADA:
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
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
} // Asegúrate de que esta última llave cierre la clase por completo