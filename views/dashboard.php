<?php
require_once dirname(__FILE__, 2) . '/helpers/auth_helper.php';
requireLogin();

require_once dirname(__FILE__, 2) . '/config/db.php'; // conexión PDO

// Conexión a la base de datos
$db = (new Database())->getConnection();

// 1️⃣ Total productos
$stmt = $db->query("SELECT COUNT(*) AS total_products FROM products");
$totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// 2️⃣ Ventas del día
$stmt = $db->prepare("
    SELECT 
        COUNT(DISTINCT s.id) AS total_sales,
        IFNULL(SUM(sd.total), 0) AS total_amount
    FROM sales s
    INNER JOIN sale_details sd ON s.id = sd.saleId
    WHERE DATE(s.createdAt) = CURDATE()
      AND s.status = 'Pagado'
");
$stmt->execute();

$todaySales = $stmt->fetch(PDO::FETCH_ASSOC);
$totalSalesCount = $todaySales['total_sales'];
$totalSalesAmount = $todaySales['total_amount'];


// 3️⃣ Total clientes
$stmt = $db->query("SELECT COUNT(*) AS total_customers FROM customers");
$totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];


$title = "Dashboard - Sistema de Ventas";
include "templates/header.php";
include "templates/navbar.php";
?>

<!-- CONTENIDO -->
<div class="container mt-4">

    <!-- ENCABEZADO DASHBOARD -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3><i class="bi bi-bar-chart-fill text-primary"></i> Dashboard - Sistema de Ventas</h3>
            <p class="text-white mb-0">Bienvenido, <strong><?= htmlspecialchars(strtoupper($username)) ?></strong> <span class="badge <?= $roleClass ?> me-3"><?= strtoupper($role) ?></span></p>
        </div>
        <div>
            <button class="btn btn-secondary btn-custom me-2" onclick="location.reload()"><i class="bi bi-arrow-repeat"></i> Actualizar</button>
            <a href="/../InventarioVentas/views/sales/create.php"><button class="btn btn-primary btn-custom"><i class="bi bi-plus-lg"></i> Nueva Venta</button></a>
        </div>
    </div>

    <!-- STATUS BAR -->
    <div class="status-bar d-flex justify-content-between text-white small">
        <div><i class="bi bi-circle-fill text-success"></i> Sistema Activo</div>

        <?php
        // Establecer la zona horaria a Perú
        date_default_timezone_set('America/Lima');
        // Obtener la hora actual
        $current_time = date('g:i a');
        ?>
        <div><i class="bi bi-clock"></i> Última actualización: <?= $current_time; ?></div>
        <div><i class="bi bi-envelope"></i> <?= $_SESSION['username'] ?>@aeon.com</div>
    </div>

    <!-- CARDS DE ESTADÍSTICAS -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 h-100"> <!-- Add the h-100 class here -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-primary mb-0"><?= htmlspecialchars($totalProducts) ?></h4>
                        <p class="mb-0">Total Productos</p>
                    </div>
                    <i class="bi bi-box text-primary fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 h1-00"> <!-- Add the h-100 class here -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-success mb-0">S/ <?= number_format($totalSalesAmount, 2) ?></h4>
                        <p class="mb-0">Ventas Hoy</p>
                        <small class="text-white"><?= htmlspecialchars($totalSalesCount) ?> transacciones</small>
                    </div>
                    <i class="bi bi-cash-coin text-success fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 h-100"> <!-- Add the h-100 class here -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-info mb-0"><?= htmlspecialchars($totalCustomers) ?></h4>
                        <p class="mb-0">Clientes</p>
                    </div>
                    <i class="bi bi-people-fill text-info fs-2"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include "templates/footer.php"; ?>