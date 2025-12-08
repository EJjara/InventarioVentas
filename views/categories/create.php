<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canCreate()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
$title = "Categorías - Crear";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <h2 class="fw-bold mb-3">Nueva Categoría</h2>
            <p class="text-white">Agrega una categoría al sistema</p>

            <!-- Mensaje de Validacion -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_GET['msg'] ?? 'Ocurrió un error inesperado.') ?>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Categoria guardada correctamente.
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="/../InventarioVentas/controllers/CategoryController.php" method="POST">
                        <input type="hidden" name="action" value="store">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Ej: Electrónica">
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Escribe una breve descripción..."></textarea>
                        </div>

                        <!-- Activo -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="isActive" name="isActive" checked>
                            <label class="form-check-label" for="active">Categoría activa</label>
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