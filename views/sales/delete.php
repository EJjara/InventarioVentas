<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin();

require_once dirname(__FILE__, 2) . '/controllers/SaleController.php';

if (!isset($_GET['id'])) {
    header("Location: index.php?error=missing_id");
    exit();
}

$id = $_GET['id'];
$controller = new SaleController();

// Intentar cancelar la venta
if ($controller->cancel($id)) {
    header("Location: index.php?cancel=1");
} else {
    header("Location: index.php?error=cancel_failed");
}
exit();
