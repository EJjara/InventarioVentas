<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canCreate()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
$title = "Clientes - Crear";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="fw-bold mb-3">Nuevo Cliente</h2>
            <p class="text-white">Agrega un cliente al sistema</p>

            <!-- Mensaje de Validacion -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_GET['msg'] ?? 'Ocurrió un error inesperado.') ?>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Cliente guardado correctamente.
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="/../InventarioVentas/controllers/CustomerController.php" method="POST">
                        <input type="hidden" name="action" value="store">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Ej: Ana Martínez Jiménez">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Ej: ana@email.com">
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Ej: 555-0104">
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <textarea id="address" name="address" class="form-control" rows="2" placeholder="Ej: Av. Los Pinos 123, Lima"></textarea>
                        </div>

                        <!-- Activo -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="isActive" name="isActive" checked>
                            <label class="form-check-label" for="isActive">Cliente activo</label>
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