<?php
require_once dirname(__FILE__, 1) . '/config/db.php';

class Sale
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 🔹 Obtener todas las ventas
    public function getAll()
    {
        $sql = "
            SELECT s.*, 
                   c.name AS customerName, 
                   u.username AS userName
            FROM sales s
            INNER JOIN customers c ON s.customerId = c.id
            INNER JOIN users u ON s.userId = u.id
            ORDER BY s.id DESC
        ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener una venta por ID (con sus detalles)
    public function getById($id)
    {
        // Cabecera de venta
        $sql = "
            SELECT s.*, 
                   c.name AS customerName, 
                   u.username AS userName
            FROM sales s
            INNER JOIN customers c ON s.customerId = c.id
            INNER JOIN users u ON s.userId = u.id
            WHERE s.id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if ($sale) {
            // Detalles de la venta
            $sqlDetails = "
                SELECT d.*, p.name AS productName 
                FROM sale_details d
                INNER JOIN products p ON d.productId = p.id
                WHERE d.saleId = ?
            ";
            $stmtDetails = $this->conn->prepare($sqlDetails);
            $stmtDetails->execute([$id]);
            $sale['details'] = $stmtDetails->fetchAll();
        }

        return $sale;
    }

    // 🔹 Crear una venta (con detalles)
    public function create($data)
    {
        try {
            $this->conn->beginTransaction();

            // Insertar venta principal
            $sqlSale = "INSERT INTO sales (customerId, userId, status) VALUES (?, ?, ?)";
            $stmtSale = $this->conn->prepare($sqlSale);
            $stmtSale->execute([
                $data['customerId'],
                $data['userId'],
                $data['status'] ?? 'Pagado'
            ]);

            $saleId = $this->conn->lastInsertId();

            // Insertar los detalles
            $sqlDetail = "INSERT INTO sale_details (saleId, productId, quantity, price, total) VALUES (?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            foreach ($data['details'] as $item) {
                $stmtDetail->execute([
                    $saleId,
                    $item['productId'],
                    $item['quantity'],
                    $item['price'],
                    $item['total']
                ]);

                // Actualizar stock del producto
                $updateStock = $this->conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStock->execute([$item['quantity'], $item['productId']]);
            }

            $this->conn->commit();
            return $saleId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            echo "Error al crear venta: " . $e->getMessage();
            exit;
        }
    }

    // 🔹 Actualizar estado de una venta
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE sales SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // 🔹 Eliminar venta y sus detalles
    public function delete($id)
    {
        try {
            $this->conn->beginTransaction();

            $this->conn->prepare("DELETE FROM sale_details WHERE saleId = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM sales WHERE id = ?")->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }


    public function search($filters)
    {
        $sql = "SELECT s.*, 
                   c.name AS customerName, 
                   u.username AS userName
            FROM sales s
            INNER JOIN customers c ON c.id = s.customerId
            INNER JOIN users u ON u.id = s.userId
            WHERE 1=1";
        $params = [];

        // Filtro por nombre del cliente
        if (!empty($filters['searchName'])) {
            $sql .= " AND c.name LIKE :name";
            $params[':name'] = "%" . $filters['searchName'] . "%";
        }

        // Filtro por fecha exacta
        if (!empty($filters['fecha'])) {
            $sql .= " AND DATE(s.createdAt) = :fecha";
            $params[':fecha'] = $filters['fecha'];
        }

        // Filtro por estado
        if (!empty($filters['status'])) {
            $sql .= " AND s.status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY s.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
