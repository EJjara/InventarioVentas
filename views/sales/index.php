<?php
//index
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión
?>

<?php
require_once dirname(__FILE__, 2) . '/controllers/SaleController.php';
$saleController = new SaleController();
$filters = [
    'searchName' => $_GET['searchName'] ?? '',
    'fecha'   => $_GET['fecha'] ?? '',
    'status'     => $_GET['status'] ?? ''
];
$ventas = $saleController->search($filters);
?>

<?php
$title = "Ventas - Página Principal";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">

    <!-- Mensaje de Venta Cancelada -->
    <?php if (isset($_GET['cancel'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            La venta fue cancelada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?> Ocurrió un error al intentar cancelar esta venta.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>



    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0">Gestión de Ventas</h2>
            <p class="text-white-50 mb-0">Administra el historial de ventas</p>
        </div>
        <div>
            <a href="create.php" class="btn btn-primary me-2">+ Nueva Venta</a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Filtros</h5>
            <form method="GET" class="row g-3 align-items-end">
                <div class="row g-3 align-items-end">
                    <!-- Buscar por nombre cliente -->
                    <div class="col-md-3">
                        <label for="searchName" class="form-label">Nombre del cliente</label>
                        <input type="text" id="searchName" name="searchName"
                            value="<?= htmlspecialchars($_GET['searchName'] ?? '') ?>"
                            class="form-control" placeholder="Ej: hector, aquiles...">
                    </div>

                    <!-- Filtro por fecha -->
                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control">
                    </div>

                    <!-- Estado -->
                    <div class="col-md-2">
                        <label for="status" class="form-label">Estado</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="Pagado" <?= (($_GET['status'] ?? '') === 'Pagado') ? 'selected' : '' ?>>Pagados</option>
                            <option value="Cancelado" <?= (($_GET['status'] ?? '') === 'Cancelado') ? 'selected' : '' ?>>Cancelados</option>
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
                </div>
            </form>
        </div>
    </div>


    <!-- Listado de Ventas -->
    <h5 class="fw-bold mb-3">Ventas (<?= count($ventas) ?>)</h5>

    <?php if (empty($ventas)): ?>
        <div class="alert alert-warning">No hay registros de ventas disponibles.</div>
    <?php else: ?>
        <?php foreach ($ventas as $v): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">

                        <!-- Columna: Datos de la venta -->
                        <div class="col-md-4">
                            <p class="mb-1 fw-bold">Venta #<?= htmlspecialchars($v['id']) ?></p>
                            <p class="mb-1"><?= date('d/m/Y', strtotime($v['createdAt'])) ?></p>
                            <p class="mb-1 text-white-50"><?= htmlspecialchars($v['customerName']) ?></p>
                        </div>

                        <!-- Columna: Estado y vendedor -->
                        <div class="col-md-4">
                            <p class="mb-1">
                                Vendedor: <span class="fw-semibold"><?= htmlspecialchars($v['userName']) ?></span>
                            </p>
                            <?php
                            $badgeClass = match ($v['status']) {
                                'Pagado' => 'bg-success',
                                'Pendiente' => 'bg-warning',
                                'Cancelado' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($v['status']) ?></span>
                        </div>

                        <!-- Columna: Botones -->
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="details.php?id=<?= $v['id'] ?>" class="btn btn-primary btn-sm">Ver Detalles</a>

                                <?php if (canDelete() && $v['status'] !== 'Cancelado'): ?>
                                    <a href="delete.php?id=<?= $v['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Seguro que deseas cancelar esta venta?');">
                                        Cancelar
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?php include "../templates/footer.php"; ?>