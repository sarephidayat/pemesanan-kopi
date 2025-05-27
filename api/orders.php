<?php
// orders.php
header('Content-Type: application/json');
require_once __DIR__ . '/../shared/database.php'; // Include koneksi

// Handle adding item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $menuId = $_POST['menu_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // Get menu item details
    $menuItem = $menuModel->getById($menuId);

    if ($menuItem) {
        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if item already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $menuId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // If not found, add new item to cart
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $menuId,
                'name' => $menuItem['name'],
                'price' => $menuItem['price'],
                'quantity' => $quantity,
                'image' => $menuItem['image']
            ];
        }

        // Return success response
        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        exit;
    }
}

// Handle removing item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $menuId = $_POST['menu_id'];

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $menuId) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        // Re-index array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    // Return success response
    echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    exit;
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['cart'])) {
        // Create order
        $orderId = $orderModel->create([
            'user_id' => $_SESSION['user_id'],
            'total' => array_reduce($_SESSION['cart'], function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0),
            'status' => 'pending'
        ]);

        // Add order items
        foreach ($_SESSION['cart'] as $item) {
            $orderModel->addItem($orderId, $item['id'], $item['quantity'], $item['price']);
        }

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to confirmation page
        header("Location: ../public/order_confirmation.html?id=$orderId");
        exit;
    } else {
        // Redirect to login if not logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php");
            exit;
        }
    }
}

// Get cart items for display
$cartItems = $_SESSION['cart'] ?? [];
$cartTotal = array_reduce($cartItems, function ($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);
?>

<!-- HTML for displaying orders -->
<div class="cart-section">
    <h2>Pesanan Anda</h2>

    <?php if (empty($cartItems)): ?>
        <p>Keranjang pesanan Anda kosong.</p>
    <?php else: ?>
        <div class="cart-items">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="../public/uploads/<?= htmlspecialchars($item['image']) ?>"
                            alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="cart-item-quantity">
                        <button class="quantity-btn minus" data-id="<?= $item['id'] ?>">-</button>
                        <span><?= $item['quantity'] ?></span>
                        <button class="quantity-btn plus" data-id="<?= $item['id'] ?>">+</button>
                    </div>
                    <div class="cart-item-price">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></div>
                    <button class="remove-btn" data-id="<?= $item['id'] ?>">×</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-total">
            <div>Total</div>
            <div>Rp <?= number_format($cartTotal, 0, ',', '.') ?></div>
        </div>

        <form method="POST" action="orders.php">
            <button type="submit" name="checkout" class="checkout-btn">Lanjutkan ke Pembayaran</button>
        </form>
    <?php endif; ?>
</div>

<script>
    // JavaScript for handling cart interactions
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners for quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const menuId = this.dataset.id;
                const isPlus = this.classList.contains('plus');

                fetch('orders.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `menu_id=${menuId}&quantity=${isPlus ? 1 : -1}&add_to_cart=1`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            });
        });

        // Add event listeners for remove buttons
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const menuId = this.dataset.id;

                fetch('orders.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `menu_id=${menuId}&remove_from_cart=1`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            });
        });
    });
</script>