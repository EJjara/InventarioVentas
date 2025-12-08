<?php
require_once dirname(__FILE__, 1) . '/helpers/auth_helper.php';

require_once dirname(__FILE__, 1) . '/config/db.php';
require_once dirname(__FILE__, 1) . '/models/Sale.php';
require_once dirname(__FILE__, 1) . '/models/Product.php';

class SaleController
{
    private $saleModel;

    public function __construct()
    {
        $this->saleModel = new Sale();
    }

    // Listar todas las ventas
    public function index()
    {
        return $this->saleModel->getAll();
    }

    // Mostrar una venta por ID
    public function show($id)
    {
        return $this->saleModel->getById($id);
    }

    // Crear una nueva venta
    public function store($data)
    {
        if (empty($data['customerId']) || empty($data['userId']) || empty($data['details'])) {
            return false; // Validación básica
        }
        return $this->saleModel->create($data);
    }

    // Actualizar el estado de una venta
    public function updateStatus($id, $status)
    {
        return $this->saleModel->updateStatus($id, $status);
    }

    //Cancelar una venta
    public function cancel($id)
    {
        // Obtener la venta para validar
        $sale = $this->saleModel->getById($id);

        if (!$sale) {
            return false; // No existe
        }

        // Si ya está cancelada, no hacemos nada
        if ($sale['status'] === 'Cancelado') {
            return false;
        }

        // Cambiar el estado
        $result = $this->saleModel->updateStatus($id, 'Cancelado');

        // ✅ Crear instancia de Product
        $productModel = new Product();

        // Devolver stock al inventario
        foreach ($sale['details'] as $item) {
            $productModel->increaseStock($item['productId'], $item['quantity']);
        }

        return $result;
    }


    // Eliminar una venta
    public function destroy($id)
    {
        return $this->saleModel->delete($id);
    }

    public function search($filters)
    {
        return $this->saleModel->search($filters);
    }
}

// =============================
// Manejo del POST del formulario
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'store') {
    $controller = new SaleController();

    // // 1️⃣ Validar sesión de usuario
    if (!isset($_SESSION['user_id'])) {
        header("Location: /../InventarioVentas/views/auth/login.php");
        exit();
    }

    // 2️⃣ Obtener datos necesarios
    $userId = $_SESSION['user_id']; //$_SESSION['user']['id']
    $customerId = $_POST['customerId'] ?? null; // podrías tener un select de cliente
    $cart = $_SESSION['cart'] ?? [];

    // 3️⃣ Preparar detalles
    $details = [];
    foreach ($cart as $productId => $item) {
        $details[] = [
            'productId' => $productId,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total' => $item['price'] * $item['quantity']
        ];
    }

    // 4️⃣ Enviar datos al modelo
    $data = [
        'customerId' => $customerId,
        'userId' => $userId,
        'details' => $details
    ];

    if ($controller->store($data)) {
        unset($_SESSION['cart']); // limpiar carrito
        header("Location: /../InventarioVentas/views/sales/index.php?success=1");
        exit();
    } else {
        header("Location: /../InventarioVentas/views/sales/create.php?error=1");
        exit();
    }
}
