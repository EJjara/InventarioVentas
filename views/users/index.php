<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

if (!canManageUsers()):
    header("Location: ../dashboard.php?error=acceso_denegado");
    exit();
endif;
?>

<?php
require_once dirname(__FILE__, 2) . '/controllers/UserController.php';
$userController = new UserController();
$filters = [
    'searchName' => $_GET['searchName'] ?? '',
    'role'   => $_GET['role'] ?? '',
    'status'     => $_GET['status'] ?? ''
];
$usuarios = $userController->search($filters);
?>

<?php
$title = "Usuarios - Página Principal";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">

    <!-- Mensaje de Eliminacion -->
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Usuario eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'foreign_key'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            No se puede eliminar este usuario porque está asociado a un producto o venta.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?> Ocurrió un error al intentar eliminar el usuario.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold">Gestión de Usuarios</h2>
            <p class="text-white">Administra los usuarios del sistema</p>
        </div>
        <a href="create.php" class="btn btn-primary rounded">+ Nuevo Usuario</a>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <!-- Buscar por nombre -->
                <div class="col-md-3">
                    <label for="searchName" class="form-label">Nombre del usuario</label>
                    <input type="text" id="searchName" name="searchName"
                        value="<?= htmlspecialchars($_GET['searchName'] ?? '') ?>"
                        class="form-control" placeholder="Ej: juan, pedro...">
                </div>
                <!-- Rol -->
                <div class="col-md-3">
                    <label for="role" class="form-label">Rol</label>
                    <select id="role" name="role" class="form-select">
                        <option value="">Todos</option>
                        <option value="Administrador" <?= (($_GET['role'] ?? '') === 'Administrador') ? 'selected' : '' ?>>Administrador</option>
                        <option value="Gerente" <?= (($_GET['role'] ?? '') === 'Gerente') ? 'selected' : '' ?>>Gerente</option>
                        <option value="Vendedor" <?= (($_GET['role'] ?? '') === 'Vendedor') ? 'selected' : '' ?>>Vendedor</option>
                    </select>
                </div>
                <!-- Estado -->
                <div class="col-md-2">
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" <?= (($_GET['status'] ?? '') === 'active') ? 'selected' : '' ?>>Activos</option>
                        <option value="inactive" <?= (($_GET['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactivos</option>
                    </select>
                </div>
                <!-- Botones -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="index.php" class="btn btn-outline-secondary">
                        Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>


    <!-- Tabla de Usuarios -->
    <div class="card">
        <div class="card-body">
            <!-- Titulo Usuarios -->
            <h5 class="fw-bold mb-3">Lista de Usuarios (<?= count($usuarios) ?>)</h5>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>USUARIO</th>
                            <th>ROL</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-white">No hay usuarios registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>

                                    <!-- Rol con color -->
                                    <td>
                                        <span class="badge 
                        <?= $u['role'] === 'Administrador' ? 'bg-danger' : ($u['role'] === 'Gerente' ? 'bg-primary' : 'bg-success') ?>">
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>

                                    <!-- Estado con color -->
                                    <td>
                                        <span class="badge <?= $u['isActive'] ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $u['isActive'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>

                                    <!-- Botones -->
                                    <td>
                                        <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary me-1">Editar</a>
                                        <a href="delete.php?id=<?= $u['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include "../templates/footer.php"; ?>