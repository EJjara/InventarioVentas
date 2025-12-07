<?php
require_once __DIR__ . '/controllers/CustomerController.php';

$customerController = new CustomerController();

// Obtener clientes
$customers = $customerController->index();

echo "<h2>🧾 Listado de Clientes</h2>";
echo "<pre>";
print_r($customers);
echo "</pre>";
