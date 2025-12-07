<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow w-100" style="max-width: 400px;">
      <div class="card-body text-center">
        <!-- Icono -->
        <div class="mb-3">
          <i class="bi bi-person-circle fs-1 text-primary"></i>
        </div>
        <!-- Título y subtítulo -->
        <h2 class="mb-1">Sistema de Inventario y Ventas</h2>
        <p class="text-muted mb-4">Inicia sesión en tu cuenta</p>

        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger">Usuario o contraseña incorrectos.</div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="../../controllers/AuthController.php" method="POST">
          <div class="mb-3 text-start">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="admin" required>
          </div>
          <div class="mb-3 text-start">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="admin123" required>
          </div>
          <button type="submit" name="login" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>