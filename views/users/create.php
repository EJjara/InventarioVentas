<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canManageUsers()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
$title = "Usuarios - Crear";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4 d-flex justify-content-center">
    <div class="card shadow-lg w-50">
        <div class="card-body">
            <h2 class="fw-bold mb-3 text-primary">Nuevo Usuario</h2>
            <p class="text-muted">Agrega un usuario al sistema</p>

            <!-- Mensaje de Validacion -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_GET['msg'] ?? 'Ocurrió un error inesperado.') ?>
                </div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Usuario guardado correctamente.
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="/../InventarioVentas/controllers/UserController.php" method="POST">
                <input type="hidden" name="action" value="store">

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Ej: admin123">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="********">
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select id="role" name="role" class="form-select">
                        <option selected disabled>Selecciona un rol</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Vendedor">Vendedor</option>
                    </select>
                </div>

                <!-- Activo -->
                <div class="form-check mb-4">
                    <input type="checkbox" id="isActive" name="isActive" class="form-check-input" checked>
                    <label for="isActive" class="form-check-label">Usuario activo</label>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end">
                    <a href="index.php" class="btn btn-outline-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../templates/footer.php"; ?>