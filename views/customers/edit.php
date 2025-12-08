<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canEdit()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
require_once dirname(__FILE__, 2) . '/controllers/CustomerController.php';
$controller = new CustomerController();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$customer = $controller->show($_GET['id']);
?>

<?php
$title = "Clientes - Editar";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="fw-bold mb-3">Editar Cliente</h2>
            <p class="text-white">Modifica la información del cliente</p>

            <!-- Mensaje de Validacion -->
            <?php if (isset($_GET['error']) && isset($_GET['msg'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success text-center">
                    Cliente actualizado correctamente.
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="card">
                <div class="card-body">
                    <form action="/../InventarioVentas/controllers/CustomerController.php" method="POST">

                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($customer['id']) ?>">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']) ?>">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>">
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']) ?>">
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <textarea id="address" name="address" class="form-control" rows="2"><?= htmlspecialchars($customer['address']) ?></textarea>
                        </div>

                        <!-- Activo -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="isActive" name="isActive" <?= $customer['isActive'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Cliente activo</label>
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