<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Customer.php';

$productModel = new Product();
$products = $productModel->getAll();

$customerModel = new Customer();
$customers = $customerModel->getAll();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<?php include_once __DIR__ . '/../templates/header.php'; ?>
<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold">Nueva Venta</h2>
        </div>
        <a href="/../InventarioVentas/views/sales/index.php" class="btn btn-primary mb-3">Ver ventas</a>
    </div>

    <!-- Agregar producto al carrito -->
    <form action="/../InventarioVentas/controllers/cart_add.php" method="POST" class="row g-2 mb-4">
        <div class="col-md-4">
            <select name="product_id" class="form-select" required>
                <option value="">Seleccionar producto</option>
                <?php foreach ($products as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['name'] ?> - S/<?= $p['price'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Agregar</button>
        </div>
        <div class="col-md-2">
            <a href="/../InventarioVentas/controllers/cart_clear.php" class="btn btn-danger w-100">Vaciar</a>
        </div>
    </form>

    <!-- Tabla del carrito -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cart)): ?>
                <tr>
                    <td colspan="5" class="text-center">No hay productos en el carrito</td>
                </tr>
            <?php else: ?>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td>S/<?= number_format($item['price'] ?? 0, 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>S/<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        <td>
                            <a href="/../InventarioVentas/controllers/cart_remove.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger">Quitar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="d-flex justify-content-end">
        <h4>Total: S/<?= number_format($total, 2) ?></h4>
    </div>

    <?php if (!empty($cart)): ?>
        <div class="card shadow-sm mt-4 border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-person-check me-2"></i> Confirmar Venta
                </h5>

                <form action="/../InventarioVentas/controllers/SaleController.php" method="POST" class="row g-3 align-items-end">

                    <!-- Selección del cliente -->
                    <div class="col-md-6">
                        <label for="customerId" class="form-label fw-semibold">Cliente</label>
                        <select name="customerId" id="customerId" class="form-select shadow-sm" required>
                            <option value="" selected disabled>Seleccione un cliente...</option>
                            <?php foreach ($customers as $c): ?>
                                <?php if ($c['isActive']): ?>
                                    <option value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['name']) ?>
                                        <?php if (!empty($c['email'])): ?>
                                            (<?= htmlspecialchars($c['email']) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Botón de confirmación ajustado -->
                    <div class="col-md-auto">
                        <input type="hidden" name="action" value="store">
                        <button type="submit" class="btn btn-success shadow-sm">
                            Confirmar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>