<!-- templates/header.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? "Sistema de Ventas"; ?></title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #0d1117;
      color: #fff;
    }
    .navbar {
      background-color: #161b22;
    }
    .card {
      border: none;
      border-radius: 12px;
      background-color: #1e232a;
      color: #fff;
    }
    .card i { font-size: 2rem; }
    .status-bar {
      border-top: 1px solid #2c323a;
      border-bottom: 1px solid #2c323a;
      padding: 10px 0;
      margin: 20px 0;
    }
    .btn-custom { border-radius: 8px; }
  </style>
</head>
<body>
