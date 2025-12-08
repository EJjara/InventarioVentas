<?php
require_once __DIR__ . '/../../helpers/auth_helper.php';
requireLogin(); // Bloquea acceso si no hay sesión

// Asignar variables
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Opcional: estilos diferentes por rol
$roleClass = match ($role) {
  'Administrador' => 'bg-danger',
  'Gerente' => 'bg-warning text-dark',
  'Vendedor' => 'bg-success',
  default => 'bg-secondary'
};
?>
<!-- NAVBAR SUPERIOR -->
<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <a class="navbar-brand fw-bold" href="/../InventarioVentas/views/dashboard.php">AEÓN Ventas</a>
  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item"><a class="nav-link" href="/../InventarioVentas/views/products/index.php"><i class="bi bi-box"></i> Productos</a></li>
    <li class="nav-item"><a class="nav-link" href="/../InventarioVentas/views/categories/index.php"><i class="bi bi-tags"></i> Categorías</a></li>
    <li class="nav-item"><a class="nav-link" href="/../InventarioVentas/views/customers/index.php"><i class="bi bi-people"></i> Clientes</a></li>
    <li class="nav-item"><a class="nav-link" href="/../InventarioVentas/views/sales/index.php"><i class="bi bi-cash-stack"></i> Ventas</a></li>

    <!-- Este solo lo ve el Administrador -->
    <?php if (canManageUsers()): ?>
      <li class="nav-item"><a class="nav-link" href="/../InventarioVentas/views/users/index.php"><i class="bi bi-person-gear"></i> Usuarios</a></li>
    <?php endif; ?>

  </ul>
  <div class="d-flex align-items-center">
    <span class="me-2"><?= htmlspecialchars(strtoupper($username)) ?></span>
    <span class="badge <?= $roleClass ?> me-3"><?= strtoupper($role) ?></span>
    <a href="/../InventarioVentas/controllers/logout.php" class="btn btn-outline-light btn-sm">Salir</a>
  </div>
</nav>