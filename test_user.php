<?php
require_once __DIR__ . '/controllers/UserController.php';

$userController = new UserController();

// Obtener usuarios
$users = $userController->index();

echo "<h2>👤 Listado de Usuarios</h2>";
echo "<pre>";
print_r($users);
echo "</pre>";
