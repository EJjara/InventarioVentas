<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canEdit()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
require_once dirname(__FILE__, 2) . '/controllers/ProductController.php';
require_once dirname(__FILE__, 2) . '/models/Category.php';// 👈 Agregamos esto

$controller = new ProductController();
$categoryModel = new Category(); // 👈 Instanciamos modelo
$categories = $categoryModel->getAll(); // 👈 Obtenemos todas las categorías

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product = $controller->show($_GET['id']);
?>

<?php
$title = "Productos - Editar";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="fw-bold mb-3">Editar Producto</h2>
            <p class="text-white">Modifica la información del producto</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_GET['msg'] ?? 'Ocurrió un error inesperado.') ?>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Producto actualizado correctamente.
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="/../InventarioVentas/controllers/ProductController.php" method="POST">

                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>">
                        </div>

                        <!-- Categoría -->
                        <div class="mb-3">
                            <label for="categoryId" class="form-label">Categoría</label>
                            <select name="categoryId" id="categoryId" class="form-select">
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id']) ?>"
                                        <?= ($cat['id'] == $product['categoryId']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- SKU -->
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control" value="<?= htmlspecialchars($product['sku']) ?>">
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>

                        <!-- Precio y Stock -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Precio</label>
                                <input type="number" step="0.1" name="price" id="price" class="form-control"
                                    value="<?= htmlspecialchars($product['price']) ?>" min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="stock" class="form-control"
                                    value="<?= htmlspecialchars($product['stock']) ?>" min="0">
                            </div>
                        </div>

                        <!-- Activo -->
                        <div class="form-check mb-3">
                            <input type="checkbox" name="isActive" class="form-check-input" id="active" <?= $product['isActive'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active">Producto activo</label>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end">
                            <a href="index.php" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../templates/footer.php"; ?>