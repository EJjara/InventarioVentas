<?php
require_once dirname(__FILE__, 2) . '/controllers/ProductController.php';
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea si no hay sesión

// Validar permiso
if (!canDelete()) {
    header("Location: index.php?error=No tienes permiso para eliminar productos");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new ProductController();

    $deleted = $controller->destroy($id);

    if ($deleted) {
        header("Location: index.php?deleted=1");
        exit();
    } else {
        header("Location: index.php?error=No se pudo eliminar el producto");
        exit();
    }
} else {
    header("Location: index.php?error=ID inválido");
    exit();
}
