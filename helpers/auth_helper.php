<?php
// helpers/auth_helper.php
session_start();

/**
 * ======================================
 *  Helper de Autenticación y Autorización
 * ======================================
 * Centraliza validaciones de sesión y permisos por rol
 * para evitar repetir condicionales en cada vista.
 * 
 * Roles: Administrador, Gerente, Vendedor
 */

// 🧩 Verifica si hay una sesión activa
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// 🧩 Obtiene el rol actual del usuario
function getRole(): string {
    return $_SESSION['role'] ?? '';
}

// 🧩 Obtiene el nombre de usuario
function getUsername(): string {
    return $_SESSION['username'] ?? 'Invitado';
}

// 🔒 Redirige si no hay sesión
function requireLogin(): void {
    if (!isLoggedIn()) {
        header("Location: /../InventarioVentas/views/auth/login.php");
        exit();
    }
}

// ==========================
//  Permisos por tipo de rol
// ==========================

// ✅ Crear (productos, categorías, clientes)
function canCreate(): bool {
    return getRole() !== 'Vendedor';
}

// ✅ Editar
function canEdit(): bool {
    return in_array(getRole(), ['Administrador', 'Gerente']);
}

// ✅ Eliminar
function canDelete(): bool {
    return in_array(getRole(), ['Administrador', 'Gerente']);
}

// ✅ Gestionar usuarios
function canManageUsers(): bool {
    return getRole() === 'Administrador';
}

// ✅ Ver dashboards completos
function canViewDashboard(): bool {
    return in_array(getRole(), ['Administrador', 'Gerente', 'Vendedor']);
}
?>
