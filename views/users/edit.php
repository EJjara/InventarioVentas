<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canManageUsers()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
require_once __DIR__ . '/../../controllers/UserController.php';
$controller = new UserController();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user = $controller->show($_GET['id']);
?>

<?php
$title = "Usuarios - Editar";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4 d-flex justify-content-center">
    <div class="card shadow-lg w-50">
        <div class="card-body">
            <h2 class="fw-bold mb-3 text-primary">Editar Usuario</h2>
            <p class="text-white">Modifica la información del usuario</p>

            <!-- Mensaje de Validacion -->
            <?php if (isset($_GET['error']) && isset($_GET['msg'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success text-center">
                    Usuario actualizado correctamente.
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="/../InventarioVentas/controllers/UserController.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" >
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="Administrador" <?= $user['role'] == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                        <option value="Gerente" <?= $user['role'] == 'Gerente' ? 'selected' : '' ?>>Gerente</option>
                        <option value="Vendedor" <?= $user['role'] == 'Vendedor' ? 'selected' : '' ?>>Vendedor</option>
                    </select>
                </div>

                <!-- Activo -->
                <div class="form-check mb-4">
                    <input type="checkbox" id="isActive" name="isActive" class="form-check-input" <?= $user['isActive'] ? 'checked' : '' ?>>
                    <label for="isActive" class="form-check-label">Usuario activo</label>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end">
                    <a href="index.php" class="btn btn-outline-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../templates/footer.php"; ?>