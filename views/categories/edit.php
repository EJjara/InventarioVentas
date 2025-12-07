<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canEdit()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
require_once __DIR__ . '/../../controllers/CategoryController.php';
$controller = new CategoryController();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$category = $controller->show($_GET['id']);
?>

<?php
$title = "Categorías - Editar";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <h2 class="fw-bold mb-3">Editar Categoría</h2>
            <p class="text-white">Modifica la información de la categoría</p>

            <!-- Mensaje de Validacion -->
            <?php if (isset($_GET['error']) && isset($_GET['msg'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success text-center">
                    Categoría actualizada correctamente.
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="/../InventarioVentas/controllers/CategoryController.php" method="POST">

                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" name="id" value="<?= htmlspecialchars($category['id']) ?>">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>">
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
                        </div>

                        <!-- Activo -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="isActive" name="isActive" <?= $category['isActive'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active">Categoría activa</label>
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