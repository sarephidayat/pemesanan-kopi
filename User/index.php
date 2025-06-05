<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../login.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk makanan
$query_makanan = "SELECT * FROM tabel_menu WHERE kode_kategori = 'MKN-2156'";
$result_makanan = mysqli_query($conn, $query_makanan);
if (!$result_makanan) {
    die("Query makanan gagal: " . mysqli_error($conn));
}

// Query untuk minuman
$query_minuman = "SELECT * FROM tabel_menu WHERE kode_kategori = 'MNM-3821'";
$result_minuman = mysqli_query($conn, $query_minuman);
if (!$result_minuman) {
    die("Query minuman gagal: " . mysqli_error($conn));
}

// Query untuk dessert
$query_dessert = "SELECT * FROM tabel_menu WHERE kode_kategori = 'DST-7294'";
$result_dessert = mysqli_query($conn, $query_dessert);
if (!$result_dessert) {
    die("Query dessert gagal: " . mysqli_error($conn));
}

// Fungsi untuk menambahkan item ke keranjang
function addToCart($kode_menu, $nama, $harga, $image)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$kode_menu])) {
        $_SESSION['cart'][$kode_menu]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$kode_menu] = [
            'nama' => $nama,
            'harga' => $harga,
            'image' => $image,
            'quantity' => 1
        ];
    }
}

// Fungsi untuk mengupdate quantity item
function updateCartQuantity($kode_menu, $quantity)
{
    if (isset($_SESSION['cart'][$kode_menu])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$kode_menu]);
        } else {
            $_SESSION['cart'][$kode_menu]['quantity'] = $quantity;
        }
    }
}

// Fungsi untuk menghapus item dari keranjang
function removeFromCart($kode_menu)
{
    if (isset($_SESSION['cart'][$kode_menu])) {
        unset($_SESSION['cart'][$kode_menu]);
    }
}

// Fungsi untuk menghitung total
function calculateTotal()
{
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
    }
    return $total;
}

// Proses Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                addToCart(
                    $_POST['kode_menu'],
                    $_POST['nama'],
                    $_POST['harga'],
                    $_POST['image']
                );
                break;
            case 'update':
                updateCartQuantity($_POST['kode_menu'], intval($_POST['quantity']));
                break;
            case 'remove':
                removeFromCart($_POST['kode_menu']);
                break;
        }
    }
    // Redirect ke halaman yang sama setelah proses
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kopi Ngelak</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Kopi Ngelak</h1>
            </div>
            <div>
                <a href="logout.php" class="back-link" style="font-size: 30px;"><i class="bi bi-box-arrow-left" style="color: white;"></i></a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Menu Makanan -->
        <section class="menu-section" id="food-section">
            <h2>Menu Makanan</h2>
            <div class="menu-items" id="food-items">
                <?php while ($row = mysqli_fetch_assoc($result_makanan)): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="image/<?php echo htmlspecialchars($row['image']); ?>"
                                alt="<?php echo htmlspecialchars($row['nama']); ?>">
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($row['nama']); ?></div>
                            <div class="item-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                            <div class="item-description"><?php echo htmlspecialchars($row['deskripsi']); ?></div>

                            <!-- Form untuk menambah ke cart -->
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="add-to-cart-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="kode_menu" value="<?php echo $row['kode_menu']; ?>">
                                <input type="hidden" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>">
                                <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                                <input type="hidden" name="image" value="<?php echo htmlspecialchars($row['image']); ?>">
                                <button type="submit" class="add-to-cart">
                                    Tambah ke Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Menu Minuman -->
        <section class="menu-section">
            <h2>Menu Minuman</h2>
            <div class="menu-items">
                <?php while ($row = mysqli_fetch_assoc($result_minuman)): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="image/<?php echo htmlspecialchars($row['image']); ?>"
                                alt="<?php echo htmlspecialchars($row['nama']); ?>">
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($row['nama']); ?></div>
                            <div class="item-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                            <div class="item-description"><?php echo htmlspecialchars($row['deskripsi']); ?></div>
                            
                            <!-- Form untuk menambah ke cart -->
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="add-to-cart-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="kode_menu" value="<?php echo $row['kode_menu']; ?>">
                                <input type="hidden" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>">
                                <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                                <input type="hidden" name="image" value="<?php echo htmlspecialchars($row['image']); ?>">
                                <button type="submit" class="add-to-cart">
                                    Tambah ke Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Menu Dessert -->
        <section class="menu-section">
            <h2>Menu Dessert</h2>
            <div class="menu-items">
                <?php while ($row = mysqli_fetch_assoc($result_dessert)): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="image/<?php echo htmlspecialchars($row['image']); ?>"
                                alt="<?php echo htmlspecialchars($row['nama']); ?>">
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($row['nama']); ?></div>
                            <div class="item-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                            <div class="item-description"><?php echo htmlspecialchars($row['deskripsi']); ?></div>
                            
                            <!-- Form untuk menambah ke cart -->
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="add-to-cart-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="kode_menu" value="<?php echo $row['kode_menu']; ?>">
                                <input type="hidden" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>">
                                <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                                <input type="hidden" name="image" value="<?php echo htmlspecialchars($row['image']); ?>">
                                <button type="submit" class="add-to-cart">
                                    Tambah ke Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Keranjang Belanja (orders) -->
        <div class="cart-section mt-5">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #b55b39; color: white;">
                    <h2 class="mb-0"><i class="bi bi-cart3 me-2"></i>Pesanan Anda</h2>
                </div>
                <div class="card-body">
                    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x display-1 text-muted"></i>
                            <p class="mt-3 text-muted">Keranjang pesanan Anda kosong.</p>
                        </div>
                    <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($_SESSION['cart'] as $kode_menu => $item): ?>
                        <div class="cart-item d-flex justify-content-between align-items-center border-bottom py-3">
                            <div class="cart-item-info flex-grow-1">
                                <div class="cart-item-name fw-bold mb-1"><?php echo htmlspecialchars($item['nama']); ?></div>
                                <div class="cart-item-price text-muted">
                                    <?php echo 'Rp ' . number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?>
                                </div>
                            </div>
                            
                            <div class="cart-item-controls d-flex align-items-center gap-3">
                                <!-- Quantity Controls -->
                                <div class="quantity-controls d-flex align-items-center">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display:inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="kode_menu" value="<?php echo $kode_menu; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity'] - 1; ?>">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                style="width: 32px; height: 32px;" 
                                                <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>
                                    
                                    <span class="mx-3 fw-bold"><?php echo $item['quantity']; ?></span>
                                    
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display:inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="kode_menu" value="<?php echo $kode_menu; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                style="width: 32px; height: 32px;">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Delete Button -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display:inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="kode_menu" value="<?php echo $kode_menu; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                            style="width: 32px; height: 32px;" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-total d-flex justify-content-between align-items-center py-3 border-top">
                    <div class="fs-5 fw-bold">Total</div>
                    <div class="fs-4 fw-bold text-black"><?php echo 'Rp ' . number_format(calculateTotal(), 0, ',', '.'); ?></div>
                </div>

                <div class="d-grid mt-3">
                    <a href="order_details.php" class="btn btn-lg" style="background-color: #b55b39; color: white;">
                        <i class="bi bi-credit-card me-2"></i>Lanjutkan ke Pembayaran
                    </a>
                </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kopi Ngelak. Semua hak dilindungi.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>