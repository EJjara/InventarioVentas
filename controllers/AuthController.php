<?php
session_start();
require_once __DIR__ . '/../models/User.php';

$userModel = new User();

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = $userModel->verifyLogin($username, $password);

    if ($user) {
        // Guardar datos del usuario en sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirigir al dashboard
        header("Location: /../InventarioVentas/views/dashboard.php"); //../dashboard.php
        exit();
    } else {
        // Redirigir con error
        header("Location: ../views/auth/login.php?error=1");
        exit();
    }
}

