<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canCreate()):
    header("Location: /../InventarioVentas/views/dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
require_once __DIR__ . '/../../models/Category.php';
$categoryModel = new Category();
$categories = $categoryModel->getAll();
?>

<?php
$title = "Productos - Crear";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="fw-bold mb-3">Nuevo Producto</h2>
            <p class="text-white">Agrega un producto al sistema</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_GET['msg'] ?? 'Ocurrió un error inesperado.') ?>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Producto guardado correctamente.
                </div>
            <?php endif; ?>


            <div class="card">
                <div class="card-body">
                    <form action="/../InventarioVentas/controllers/ProductController.php" method="POST">
                        <input type="hidden" name="action" value="store">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Ej: Laptop Lenovo" >
                        </div>

                        <!-- Categoría -->
                        <div class="mb-3">
                            <label for="categoryId" class="form-label">Categoría</label>
                            <select id="categoryId" name="categoryId" class="form-select">
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id']) ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- SKU -->
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" id="sku" name="sku" class="form-control" placeholder="Ej: LP-2025-001">
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Escribe una breve descripción..."></textarea>
                        </div>

                        <!-- Precio y Stock -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Precio</label>
                                <input type="number" id="price" name="price" class="form-control" placeholder="0.00" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" id="stock" name="stock" class="form-control" placeholder="0">
                            </div>
                        </div>

                        <!-- Activo -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="isActive" name="isActive" checked>
                            <label class="form-check-label" for="isActive">Producto activo</label>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end">
                            <a href="index.php" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../templates/footer.php"; ?>