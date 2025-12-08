<?php
require_once dirname(__FILE__, 1) . '/models/Category.php';

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    // Listar todas las categorías
    public function index()
    {
        return $this->categoryModel->getAll();
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        return $this->categoryModel->getById($id);
    }

    // Crear una nueva categoría
    public function store($data)
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors[] = "El nombre de la categoria es obligatorio.";
        }

        if (empty($data['description'])) {
            $errors[] = "Debe escribir una descripcion.";
        }

        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        $result = $this->categoryModel->create($data);

        return [
            'success' => $result,
            'message' => $result ? 'Categoria creada correctamente.' : 'Error al crear categoria.'
        ];
    }

    // Actualizar una categoría
    public function update($id, $data)
    {
        $errors = [];

        // Validaciones básicas
        if (empty(trim($data['name']))) {
            $errors[] = "El nombre de la categoría es obligatorio.";
        }

        if (empty(trim($data['description']))) {
            $errors[] = "Debe escribir una descripción.";
        }

        // Si hay errores, retornar mensaje unificado
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        // Si pasa las validaciones, intenta actualizar
        $result = $this->categoryModel->update($id, $data);

        return [
            'success' => $result,
            'message' => $result ? 'Categoría modificada correctamente.' : 'Error al modificar la categoría.'
        ];
    }


    // Eliminar una categoría
    public function destroy($id)
    {
        $result = $this->categoryModel->delete($id);

        if ($result === true) {
            header("Location: /../InventarioVentas/views/categories/index.php?deleted=1");
            exit();
        } elseif ($result === 'constraint_error') {
            header("Location: /../InventarioVentas/views/categories/index.php?error=foreign_key");
            exit();
        } else {
            header("Location: /../InventarioVentas/views/categories/index.php?error=unknown");
            exit();
        }
    }

    public function search($filters)
    {
        return $this->categoryModel->search($filters);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller = new CategoryController();

    if ($_POST['action'] === 'store') {
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->store($data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/categories/index.php?success=1");
        } else {
            header("Location: /../InventarioVentas/views/categories/create.php?error=1&msg=" . urlencode($response['message']));
        }
        exit();
    } elseif ($_POST['action'] === 'update') {
        $controller = new CategoryController();

        $id = $_POST['id'];
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->update($id, $data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/categories/index.php?updated=1");
        } else {
            header("Location: /../InventarioVentas/views/categories/edit.php?id=$id&error=1&msg=" . urlencode($response['message']));
        }
        exit();
    }
}
