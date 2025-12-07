<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/controllers/ProductController.php';

$productController = new ProductController();
$products = $productController->index();

echo "<h2>📦 Listado de Productos</h2>";
echo "<pre>";
print_r($products);
echo "</pre>";