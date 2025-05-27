<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../shared/database.php';
session_start();

$db = new Database();
$response = ['success' => false];

try {
    // Handle POST requests (modifikasi keranjang)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_to_cart'])) {
            $menuId = $_POST['menu_id'];
            $menuModel = new Menu($db);
            $menuItem = $menuModel->getById($menuId);

            if ($menuItem) {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $found = false;
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $menuId) {
                        $item['quantity'] += 1;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $_SESSION['cart'][] = [
                        'id' => $menuId,
                        'name' => $menuItem['name'],
                        'price' => $menuItem['price'],
                        'quantity' => 1,
                        'image' => $menuItem['image']
                    ];
                }

                $response = [
                    'success' => true,
                    'cart_count' => count($_SESSION['cart'])
                ];
            }
        } elseif (isset($_POST['update_cart'])) {
            $menuId = $_POST['menu_id'];
            $quantityChange = (int) $_POST['quantity'];

            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $menuId) {
                        $item['quantity'] += $quantityChange;
                        if ($item['quantity'] <= 0) {
                            $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($i) => $i['id'] != $menuId);
                        }
                        break;
                    }
                }
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }

            $response = ['success' => true];
        } elseif (isset($_POST['remove_from_cart'])) {
            $menuId = $_POST['menu_id'];

            if (isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($item) => $item['id'] != $menuId);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }

            $response = ['success' => true];
        }
    }

    // Untuk GET requests (ambil data keranjang)
    $cartItems = $_SESSION['cart'] ?? [];
    $cartTotal = array_reduce($cartItems, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

    $response = [
        'success' => true,
        'cart_items' => $cartItems,
        'cart_count' => count($cartItems),
        'cart_total' => $cartTotal
    ];

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);