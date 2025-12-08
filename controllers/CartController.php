<?php
session_start();
require_once dirname(__FILE__, 1) . '/config/db.php';
require_once dirname(__FILE__, 1) . '/models/Product.php';


class CartController
{
    // Inicializa el carrito si no existe
    private static function initCart()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // 🛒 Agregar producto
    public static function add()
    {
        self::initCart();

        $product_id = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$product_id) {
            die("Error: producto no especificado");
        }

        $productModel = new Product();
        $product = $productModel->getById($product_id);

        if (!$product) {
            die("Error: producto no encontrado");
        }

        // Si ya existe el producto en el carrito, solo actualiza cantidad
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }

        header("Location: /../InventarioVentas/views/sales/create.php");
        exit();
    }

    // ❌ Eliminar producto del carrito
    public static function remove()
    {
        self::initCart();
        $product_id = $_GET['id'] ?? null;

        if ($product_id && isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }

        header("Location: /../InventarioVentas/views/sales/create.php");
        exit();
    }

    // 🧹 Vaciar carrito
    public static function clear()
    {
        unset($_SESSION['cart']);
        header("Location: /../InventarioVentas/views/sales/create.php");
        exit();
    }
}
