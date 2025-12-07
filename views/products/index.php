<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión
?>

<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';

$productController = new ProductController();
$filters = [
    'searchName' => $_GET['searchName'] ?? '',
    'category'   => $_GET['category'] ?? '',
    'stock'      => $_GET['stock'] ?? '',
    'status'     => $_GET['status'] ?? ''
];
$products = $productController->search($filters);

?>

<div class="container mt-4">

    <!-- Mensaje de Eliminacion -->
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Producto eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'foreign_key'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            No se puede eliminar este producto porque está asociado a una o más ventas.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?> Ocurrió un error al intentar eliminar el producto.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">Lista de Productos</h2>

        <?php if (canCreate()): ?>
            <a href="create.php" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        <?php endif; ?>

    </div>


    <!-- Panel de Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <!-- Buscar por nombre -->
                <div class="col-md-2">
                    <label for="searchName" class="form-label">Nombre del producto</label>
                    <input type="text" id="searchName" name="searchName"
                        value="<?= htmlspecialchars($_GET['searchName'] ?? '') ?>"
                        class="form-control" placeholder="Ej: laptop, mouse...">
                </div>

                <!-- Categoría -->
                <div class="col-md-2">
                    <label for="category" class="form-label">Categoría</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Todas</option>
                        <?php
                        require_once __DIR__ . '/../../controllers/CategoryController.php';
                        $catController = new CategoryController();
                        $categorias = $catController->index();
                        foreach ($categorias as $c):
                        ?>
                            <option value="<?= $c['id'] ?>"
                                <?= isset($_GET['category']) && $_GET['category'] == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Stock -->
                <div class="col-md-2">
                    <label for="stock" class="form-label">Stock</label>
                    <select id="stock" name="stock" class="form-select">
                        <option value="">Todos</option>
                        <option value="low" <?= (($_GET['stock'] ?? '') === 'low') ? 'selected' : '' ?>>Stock bajo</option>
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

    <!-- Tabla de Productos -->
    <div class="table-responsive shadow rounded">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Categoría</th>
                    <th>Nombre</th>
                    <th>SKU</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['id']) ?></td>
                            <td><?= htmlspecialchars($p['categoryName']) ?></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['sku']) ?></td>
                            <td>$<?= number_format($p['price'], 2) ?></td>
                            <td><?= htmlspecialchars($p['stock']) ?></td>
                            <td>
                                <?php if ($p['isActive']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>

                                <!-- Editar -->
                                <?php if (canEdit()): ?>
                                    <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <?php endif; ?>

                                <!-- Eliminar -->
                                <?php if (canDelete()): ?>
                                    <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay productos registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>