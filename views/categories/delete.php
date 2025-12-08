<?php
require_once dirname(__FILE__, 2) . '/controllers/CategoryController.php';
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea si no hay sesión

// Validar permiso
if (!canDelete()) {
    header("Location: index.php?error=No tienes permiso para eliminar categorias");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new CategoryController();

    $deleted = $controller->destroy($id);

    if ($deleted) {
        header("Location: index.php?deleted=1");
        exit();
    } else {
        header("Location: index.php?error=No se pudo eliminar la categoria");
        exit();
    }
} else {
    header("Location: index.php?error=ID inválido");
    exit();
}
