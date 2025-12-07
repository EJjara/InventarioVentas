<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    // Listar productos
    public function index()
    {
        return $this->productModel->getAll();
    }

    // Ver producto
    public function show($id)
    {
        return $this->productModel->getById($id);
    }

    // Crear producto
    public function store($data)
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors[] = "El nombre del producto es obligatorio.";
        }

        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors[] = "El precio debe ser un número mayor que 0.";
        }

        if (empty($data['categoryId'])) {
            $errors[] = "Debe seleccionar una categoría.";
        }

        if (!empty($data['stock']) && (!is_numeric($data['stock']) || $data['stock'] < 0)) {
            $errors[] = "El stock debe ser un número no negativo.";
        }

        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        $result = $this->productModel->create($data);

        return [
            'success' => $result,
            'message' => $result ? 'Producto creado correctamente.' : 'Error al crear producto.'
        ];
    }


    // Actualizar producto
    public function update($id, $data)
    {
        if (empty($data['name']) || empty($data['price'])) {
            return false;
        }
        return $this->productModel->update($id, $data);
    }

    // Eliminar producto
    public function destroy($id)
    {
        $result = $this->productModel->delete($id);

        if ($result === true) {
            header("Location: /../InventarioVentas/views/products/index.php?deleted=1");
            exit();
        } elseif ($result === 'constraint_error') {
            header("Location: /../InventarioVentas/views/products/index.php?error=foreign_key");
            exit();
        } else {
            header("Location: /../InventarioVentas/views/products/index.php?error=unknown");
            exit();
        }
    }

    public function search($filters) {
        return $this->productModel->search($filters);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller = new ProductController();

    if ($_POST['action'] === 'store') {
        $data = [
            'categoryId' => $_POST['categoryId'],
            'name' => $_POST['name'],
            'sku' => $_POST['sku'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->store($data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/products/index.php?success=1");
        } else {
            header("Location: /../InventarioVentas/views/products/create.php?error=1&msg=" . urlencode($response['message']));
        }
        exit();
    } elseif ($_POST['action'] === 'update') {
        $controller = new ProductController();

        $id = $_POST['id'];
        $data = [
            'categoryId' => $_POST['categoryId'],
            'name' => $_POST['name'],
            'sku' => $_POST['sku'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $result = $controller->update($id, $data);

        if ($result) {
            header("Location: /../InventarioVentas/views/products/index.php?updated=1");
        } else {
            header("Location: /../InventarioVentas/views/products/edit.php?id=$id&error=1");
        }
        exit();
    }
}
