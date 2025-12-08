<?php
require_once __DIR__ . '/../../controllers/UserController.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea si no hay sesión

// Validar permiso
if (!canManageUsers()) {
    header("Location: index.php?error=No tienes permiso para eliminar usuarios");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new UserController();

    $deleted = $controller->destroy($id);

    if ($deleted) {
        header("Location: index.php?deleted=1");
        exit();
    } else {
        header("Location: index.php?error=No se pudo eliminar el usuario");
        exit();
    }
} else {
    header("Location: index.php?error=ID inválido");
    exit();
}
