<?php
require_once dirname(__FILE__, 1) . '/models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Listar usuarios
    public function index()
    {
        return $this->userModel->getAll();
    }

    // Ver un usuario
    public function show($id)
    {
        return $this->userModel->getById($id);
    }

    // Crear usuario
    public function store($data)
    {
        $errors = [];

        if (empty(trim($data['username']))) {
            $errors[] = "El nombre del usuario es obligatorio.";
        }

        if (empty($data['password'])) {
            $errors[] = "Debe escribir una contraseña.";
        }

        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        $result = $this->userModel->create($data);

        return [
            'success' => $result,
            'message' => $result ? 'Usuario creado correctamente.' : 'Error al crear usuario.'
        ];
    }


    // Actualizar usuario (sin password)
    public function update($id, $data)
    {
        $errors = [];

        // Validaciones básicas
        if (empty(trim($data['username']))) {
            $errors[] = "El nombre del usuario es obligatorio.";
        }

        // if (empty(trim($data['address']))) {
        //     $errors[] = "Debe escribir una contraseña.";
        // }

        // Si hay errores, retornar mensaje unificado
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => implode(" ", $errors)
            ];
        }

        // Si pasa las validaciones, intenta actualizar
        $result = $this->userModel->update($id, $data);

        return [
            'success' => $result,
            'message' => $result ? 'Usuario modificado correctamente.' : 'Error al modificar la usuario.'
        ];
    }

    // Cambiar contraseña
    public function changePassword($id, $newPassword)
    {
        return $this->userModel->updatePassword($id, $newPassword);
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $result = $this->userModel->delete($id);

        if ($result === true) {
            header("Location: /../InventarioVentas/views/users/index.php?deleted=1");
            exit();
        } elseif ($result === 'constraint_error') {
            header("Location: /../InventarioVentas/views/users/index.php?error=foreign_key");
            exit();
        } else {
            header("Location: /../InventarioVentas/views/users/index.php?error=unknown");
            exit();
        }
    }

    // Validar login
    public function login($username, $password)
    {
        return $this->userModel->verifyCredentials($username, $password);
    }

    public function search($filters)
    {
        return $this->userModel->search($filters);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller = new UserController();

    if ($_POST['action'] === 'store') {
        $data = [
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'role' => $_POST['role'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->store($data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/users/index.php?success=1");
        } else {
            header("Location: /../InventarioVentas/views/users/create.php?error=1&msg=" . urlencode($response['message']));
        }
        exit();
    } elseif ($_POST['action'] === 'update') {
        $controller = new UserController();

        $id = $_POST['id'];
        $data = [
            'username' => $_POST['username'],
            // 'password' => $_POST['password'],
            'role' => $_POST['role'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        $response = $controller->update($id, $data);

        if ($response['success']) {
            header("Location: /../InventarioVentas/views/users/index.php?updated=1");
        } else {
            header("Location: /../InventarioVentas/views/users/edit.php?id=$id&error=1&msg=" . urlencode($response['message']));
        }
        exit();
    }
}
