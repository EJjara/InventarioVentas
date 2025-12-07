<?php
//puente entre la base de datos y el controlador.
require_once __DIR__ . '/../config/db.php';

class Product
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Obtener todos los productos
    public function getAll()
    {
        $sql = "SELECT p.*, c.name AS categoryName 
                FROM products p
                INNER JOIN categories c ON p.categoryId = c.id
                ORDER BY p.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener producto por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear producto
    public function create($data)
    {
        try {
            $sql = "INSERT INTO products (categoryId, name, sku, description, price, stock, isActive)
                VALUES (:categoryId, :name, :sku, :description, :price, :stock, :isActive)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            echo "Error al crear producto: " . $e->getMessage();
            return false;
        }
    }

    // Actualizar producto
    // public function update($id, $data) {
    //     $sql = "UPDATE products SET 
    //                 categoryId = :categoryId,
    //                 name = :name,
    //                 sku = :sku,
    //                 description = :description,
    //                 price = :price,
    //                 stock = :stock,
    //                 isActive = :isActive
    //             WHERE id = :id";
    //     $stmt = $this->conn->prepare($sql);
    //     $data[":id"] = $id;
    //     return $stmt->execute($data);
    // }

    // CORREGIDO update()
    public function update($id, $data)
    {
        $sql = "UPDATE products SET 
                categoryId = :categoryId,
                name = :name,
                sku = :sku,
                description = :description,
                price = :price,
                stock = :stock,
                isActive = :isActive
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Agregar id al array (sin :)
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function increaseStock($productId, $quantity)
    {
        $sql = "UPDATE products SET stock = stock + ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$quantity, $productId]);
    }


    // Eliminar producto
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
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
                error_log("Error eliminando producto: " . $e->getMessage());
                return false;
            }
        }
    }


    public function search($filters)
    {
        $sql = "SELECT p.*, c.name AS categoryName 
            FROM products p
            INNER JOIN categories c ON c.id = p.categoryId
            WHERE 1=1";
        $params = [];

        // Filtro por nombre
        if (!empty($filters['searchName'])) {
            $sql .= " AND p.name LIKE :name";
            $params[':name'] = "%" . $filters['searchName'] . "%";
        }

        // Filtro por categoría
        if (!empty($filters['category'])) {
            $sql .= " AND p.categoryId = :category";
            $params[':category'] = $filters['category'];
        }

        // Filtro por stock bajo
        if ($filters['stock'] === 'low') {
            $sql .= " AND p.stock < 5";
        }

        // Filtro por estado
        if ($filters['status'] === 'active') {
            $sql .= " AND p.isActive = 1";
        } elseif ($filters['status'] === 'inactive') {
            $sql .= " AND p.isActive = 0";
        }

        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
