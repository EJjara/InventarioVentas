<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión
?>

<?php
require_once dirname(__FILE__, 2) . '/controllers/SaleController.php';

// Verificamos si viene un ID válido en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$saleId = $_GET['id'];

// Obtenemos los datos de la venta
$controller = new SaleController();
$sale = $controller->show($saleId);

// Si no existe la venta, redirigimos
if (!$sale) {
    header('Location: index.php');
    exit;
}
?>

<?php
$title = "Ventas - Detalle de Venta";
include "../templates/header.php";
include "../templates/navbar.php";
?>

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <!-- Encabezado de la boleta -->
            <div class="text-center mb-4">
                <h3 class="fw-bold">Detalle de Venta</h3>
                <p class="text-white mb-1">Número de Venta: <span class="fw-semibold">#<?= htmlspecialchars($sale['id']) ?></span></p>
                <p class="text-white mb-1">Cliente: <span class="fw-semibold"><?= htmlspecialchars($sale['customerName']) ?></span></p>
                <p class="text-white mb-1">Vendedor: <span class="fw-semibold"><?= htmlspecialchars($sale['userName']) ?></span></p>
                <p class="text-white">Fecha: <span class="fw-semibold"><?= date('d/m/Y H:i', strtotime($sale['createdAt'])) ?></span></p>
            </div>

            <!-- Tabla de productos -->
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalGeneral = 0;
                    foreach ($sale['details'] as $index => $item):
                        $totalGeneral += $item['total'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item['productName']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($item['quantity']) ?></td>
                            <td class="text-end">S/ <?= number_format($item['price'], 2) ?></td>
                            <td class="text-end">S/ <?= number_format($item['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold">S/ <?= number_format($totalGeneral, 2) ?></td>
                    </tr>
                </tfoot>
            </table>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-secondary">Cerrar</a>
                <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<?php include "../templates/footer.php"; ?>