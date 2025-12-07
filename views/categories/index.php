<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión
?>

<?php
// Datos
require_once __DIR__ . '/../../controllers/CategoryController.php';

$categoryController = new CategoryController();
$filters = [
    'searchName' => $_GET['searchName'] ?? '',
    'status'     => $_GET['status'] ?? ''
];
$categorias = $categoryController->search($filters);
?>

<?php
// index.php de categorías
$title = "Categorías - Página Principal"; // título dinámico para el header
include '../templates/header.php';
include '../templates/navbar.php';
?>

<div class="container mt-4">

    <!-- Mensaje de Eliminacion -->
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Categoria eliminada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'foreign_key'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            No se puede eliminar esta categoria porque está asociada a uno o más productos.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?> Ocurrió un error al intentar eliminar la categoria.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold">Gestión de Categorías</h2>
            <p class="text-white">Organiza tu inventario por categorías</p>
        </div>

        <?php if (canCreate()): ?>
            <a href="create.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Nueva Categoría</a>
        <?php endif; ?>

    </div>

    <!-- Panel de Filtros  -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Filtros</h5>
            <form method="GET" class="row g-3 align-items-center">
                <!-- Nombre -->
                <div class="col-md-4">
                    <input type="text" id="searchName" name="searchName" class="form-control"
                        placeholder="Ej: electrónica, hogar...">
                </div>
                <!-- Estado -->
                <div class="col-md-3">
                    <select id="status" name="status" class="form-select">
                        <option value="">Todas</option>
                        <option value="active">Activas</option>
                        <option value="inactive">Inactivas</option>
                    </select>
                </div>
                <!-- Botones -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="index.php">
                        <button class="btn btn-outline-secondary">
                            Limpiar Filtros
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Listado de categorías -->
    <!-- Título Categorías -->
    <h5 class="fw-bold mb-3 text-white">
        Categorías (<?= count($categorias) ?>)
    </h5>

    <div class="row g-3">
        <?php if (empty($categorias)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert" style="background-color: #1e1f21; color: #ccc; border: 1px solid #444;">
                    <i class="bi bi-folder-x" style="font-size: 1.5rem;"></i><br>
                    No hay categorías registradas actualmente.<br>
                    <?php if (canEdit()): ?>
                        <a href="create.php" class="btn btn-sm btn-primary mt-2">Crear nueva categoría</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Ciclo de las cartas de categorías -->
            <?php foreach ($categorias as $cat): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100" style="background-color: #343a40; color: white; border-radius: 10px;">
                        <div class="card-body">
                            <!-- Nombre + Estado -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0"><?= htmlspecialchars($cat['name']) ?></h5>
                                <span class="badge bg-<?= $cat['isActive'] ? 'success' : 'secondary' ?>">
                                    <?= $cat['isActive'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </div>

                            <p class="mb-2"><?= htmlspecialchars($cat['description']) ?></p>
                            <small>Creada: <?= date('d/m/Y', strtotime($cat['createdAt'])) ?></small>
                        </div>

                        <!-- Footer con acciones -->
                        <div class="card-footer d-flex justify-content-between" style="background-color: #343a40; border-top: none; padding: 0.75rem;">
                            <?php if (canEdit()): ?>
                                <a href="edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <?php endif; ?>

                            <?php if (canDelete()): ?>
                                <a href="delete.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                    Eliminar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php include '../templates/footer.php'; ?>