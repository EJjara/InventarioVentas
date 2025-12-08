<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión
?>

<?php
require_once dirname(__FILE__, 2) . '/controllers/CustomerController.php';

$customerController = new CustomerController();
$filters = [
    'searchName' => $_GET['searchName'] ?? '',
    'status'     => $_GET['status'] ?? ''
];
$clientes = $customerController->search($filters);
?>

<?php
$title = "Clientes - Página Principal";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">

    <!-- Mensaje de Eliminacion -->
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Cliente eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'foreign_key'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            No se puede eliminar este cliente porque está asociado a una o más ventas.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?> Ocurrió un error al intentar eliminar el cliente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold">Gestión de Clientes</h2>
            <p class="text-white">Administra tu base de clientes</p>
        </div>

        <?php if (canCreate()): ?>
            <a href="create.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Agregar Cliente</a>
        <?php endif; ?>

    </div>

    <!-- Panel de Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <!-- Buscar por nombre -->
                <div class="col-md-2">
                    <label for="searchName" class="form-label">Nombre del cliente</label>
                    <input type="text" id="searchName" name="searchName"
                        value="<?= htmlspecialchars($_GET['searchName'] ?? '') ?>"
                        class="form-control" placeholder="Ej: hector, aquiles...">
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


    <!-- Titulo Clientes -->
    <h5 class="fw-bold mb-3">Clientes (<?= count($clientes) ?>)</h5>

    <div class="row g-3">
        <?php if (empty($clientes)): ?> <!-- $categorias -->
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert" style="background-color: #1e1f21; color: #ccc; border: 1px solid #444;">
                    <i class="bi bi-folder-x" style="font-size: 1.5rem;"></i><br>
                    No hay clientes registradas actualmente.<br>
                    <?php if (canEdit()): ?>
                        <a href="create.php" class="btn btn-sm btn-primary mt-2">Crear nueva cliente</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Ciclo de las cartas de categorías -->
            <?php foreach ($clientes as $cliente): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">

                            <!-- Nombre y Estado -->
                            <div class="col-md-3">
                                <h5 class="fw-bold mb-0"><?= htmlspecialchars($cliente['name']) ?></h5>
                                <span class="badge <?= $cliente['isActive'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $cliente['isActive'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </div>

                            <!-- Información de Contacto -->
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
                                <p class="mb-1"><strong>Teléfono:</strong> <?= htmlspecialchars($cliente['phone']) ?></p>
                                <p class="mb-0"><strong>Dirección:</strong> <?= htmlspecialchars($cliente['address']) ?></p>
                            </div>

                            <!-- Fecha de Registro -->
                            <div class="col-md-2">
                                <p class="mb-0"><strong>Registro:</strong><br><?= date('d/m/Y', strtotime($cliente['createdAt'])) ?></p>
                            </div>

                            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                <!-- Editar -->
                                <?php if (canEdit()): ?>
                                    <a href="edit.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-primary me-2">Editar</a>
                                <?php endif; ?>

                                <!-- Eliminar -->
                                <?php if (canDelete()): ?>
                                    <a href="delete.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Seguro de eliminar este cliente?');">Eliminar</a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


</div>

<?php include "../templates/footer.php"; ?>