<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/controllers/CategoryController.php';

$categoryController = new CategoryController();
$categories = $categoryController->index();

echo "<h2>📦 Listado de Categorías</h2>";
echo "<pre>";
print_r($categories);
echo "</pre>";