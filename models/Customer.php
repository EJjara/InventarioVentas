<?php
require_once __DIR__ . '/../config/db.php';

class Customer
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Obtener todos los clientes
    public function getAll()
    {
        $sql = "SELECT * FROM customers ORDER BY name ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un cliente por ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear cliente
    public function create($data)
    {
        $sql = "INSERT INTO customers (name, email, phone, address, isActive) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['isActive'] ?? 1
        ]);
    }

    // Actualizar cliente
    public function update($id, $data)
    {
        $sql = "UPDATE customers SET name=?, email=?, phone=?, address=?, isActive=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['isActive'],
            $id
        ]);
    }

    // Eliminar cliente
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM customers WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Código SQLSTATE 23000 → error de integridad referencial
            if ($e->getCode() === '23000') {
                return 'constraint_error';
            } else {
                // Opcional: puedes loguearlo para debugging
                error_log("Error eliminando cliente: " . $e->getMessage());
                return false;
            }
        }
    }

    public function search($filters)
    {
        $sql = "SELECT * FROM customers WHERE 1=1";
        $params = [];

        // Filtro por nombre
        if (!empty($filters['searchName'])) {
            $sql .= " AND name LIKE :name";
            $params[':name'] = "%" . $filters['searchName'] . "%";
        }

        // Filtro por estado (activo / inactivo)
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $sql .= " AND isActive = 1";
            } elseif ($filters['status'] === 'inactive') {
                $sql .= " AND isActive = 0";
            }
        }

        $sql .= " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
