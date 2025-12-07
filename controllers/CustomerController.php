<?php
require_once __DIR__ . '/../models/Customer.php';

class CustomerController
{
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
    }

    // Listar clientes
    public function index()
    {
        return $this->customerModel->getAll();
    }

    // Ver un cliente
    public function show($id)
    {
        return $this->customerModel->getById($id);
    }

    // Crear cliente
    public function store($data)
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors[] = "El nombre del cliente es obligatorio.";
        }

        if (empty($data['address'])) {
            $errors[] = "La dirección del cliente es obligatoria.";
        }

        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        $result = $this->customerModel->create($data);

        return [
            'success' => $result,
            'message' => $result ? 'Cliente creado correctamente.' : 'Error al crear cliente.'
        ];
    }


    // Actualizar cliente
    public function update($id, $data)
    {
        $errors = [];

        // Validaciones básicas
        if (empty(trim($data['name']))) {
            $errors[] = "El nombre del cliente es obligatorio.";
        }

        if (empty(trim($data['address']))) {
            $errors[] = "La dirección del cliente es obligatorio.";
        }

        // Si hay errores, retornar mensaje unificado
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        // Si pasa las validaciones, intenta actualizar
        $result = $this->customerModel->update($id, $data);

        return [
            'success' => $result,
            'message' => $result ? 'Cliente modificado correctamente.' : 'Error al modificar el cliente.'
        ];
    }


    // Eliminar cliente
    public function destroy($id)
    {
        $result = $this->customerModel->delete($id);

        if ($result === true) {
            header("Location: /../InventarioVentas/views/customers/index.php?deleted=1");
            exit();
        } elseif ($result === 'constraint_error') {
            header("Location: /../InventarioVentas/views/customers/index.php?error=foreign_key");
            exit();
        } else {
            header("Location: /../InventarioVentas/views/customers/index.php?error=unknown");
            exit();
        }
    }

    public function search($filters)
    {
        return $this->customerModel->search($filters);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller = new CustomerController();

    if ($_POST['action'] === 'store') {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->store($data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/customers/index.php?success=1");
        } else {
            header("Location: /../InventarioVentas/views/customers/create.php?error=1&msg=" . urlencode($response['message']));
        }
        exit();
    } elseif ($_POST['action'] === 'update') {
        $controller = new CustomerController();

        $id = $_POST['id'];
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->update($id, $data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/customers/index.php?updated=1");
        } else {
            header("Location: /../InventarioVentas/views/customers/edit.php?id=$id&error=1&msg=" . urlencode($response['message']));
        }
        exit();
    }
}
