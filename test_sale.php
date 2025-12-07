<?php
require_once __DIR__ . '/controllers/SaleController.php';

$saleController = new SaleController();

// Ver todas las ventas
$sales = $saleController->index();

echo "<h2>🧾 Ventas registradas</h2>";
echo "<pre>";
print_r($sales);
echo "</pre>";

// Ver una venta con detalles
// $sale = $saleController->show(1);
// print_r($sale);
