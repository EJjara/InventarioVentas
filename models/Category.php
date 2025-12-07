<?php
require_once __DIR__ . '/../config/db.php';

class Category
{
    private $conn;

    public function __construct()
    {
        $db = new Database();           // ✅ crea una instancia de la clase Database
        $this->conn = $db->getConnection(); // ✅ obtiene la conexión PDO
    }

    public function getAll()
    {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO categories (name, description, isActive) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['isActive'] ?? 1
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE categories SET name=?, description=?, isActive=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['isActive'],
            $id
        ]);
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM categories WHERE id = :id";
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
                error_log("Error eliminando categoria: " . $e->getMessage());
                return false;
            }
        }
    }


    public function search($filters)
    {
        $sql = "SELECT * FROM categories WHERE 1=1";
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
