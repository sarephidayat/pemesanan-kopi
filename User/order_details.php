<?php
session_start();

// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Redirect jika cart kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// Fungsi untuk format rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
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

// Fungsi untuk menghitung pajak (10%)
function calculateTax()
{
    return calculateTotal() * 0.1;
}

// Fungsi untuk menghitung grand total
function calculateGrandTotal()
{
    return calculateTotal() + calculateTax();
}

// Proses form pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $table_number = intval($_POST['table_number']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Generate kode pemesanan
    $order_code = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

    // Insert ke tabel pemesanan (sesuaikan dengan struktur database Anda)
    $insert_order = "INSERT INTO tabel_pemesanan (kode_pemesanan, nama_pelanggan, nomor_meja, catatan, metode_pembayaran, total_harga, status, tanggal_pemesanan) 
                     VALUES ('$order_code', '$customer_name', $table_number, '$notes', '$payment_method', " . calculateGrandTotal() . ", 'pending', NOW())";

    if (mysqli_query($conn, $insert_order)) {
        // Insert detail pemesanan
        foreach ($_SESSION['cart'] as $kode_menu => $item) {
            $insert_detail = "INSERT INTO tabel_detail_pemesanan (kode_pemesanan, kode_menu, quantity, harga_satuan, subtotal) 
                             VALUES ('$order_code', '$kode_menu', {$item['quantity']}, {$item['harga']}, " . ($item['harga'] * $item['quantity']) . ")";
            mysqli_query($conn, $insert_detail);
        }

        // Bersihkan cart
        unset($_SESSION['cart']);

        // Redirect ke halaman sukses atau tampilkan pesan
        echo "<script>alert('Pesanan berhasil diproses! Kode pesanan: $order_code'); window.location.href='index.php';</script>";
        exit;
    } else {
        $error_message = "Gagal memproses pesanan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Kopi Nuri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/order_details.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Kopi Nuri</h1>
            </div>
            <div>
                <a href="index.php" class="back-link" style="font-size: 30px;"><i class="bi bi-box-arrow-left"></i></a>
            </div>
        </div>
    </header>

    <main class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <section class="order-section">
            <h2><i class="bi bi-receipt me-2"></i>Rincian Pesanan</h2>

            <div class="order-items">
                <?php foreach ($_SESSION['cart'] as $kode_menu => $item): ?>
                    <div class="order-item">
                        <div class="item-name"><?php echo htmlspecialchars($item['nama']); ?></div>
                        <div class="item-quantity"><?php echo $item['quantity']; ?> x</div>
                        <div class="item-price"><?php echo formatRupiah($item['harga'] * $item['quantity']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <div>Subtotal</div>
                    <div><?php echo formatRupiah(calculateTotal()); ?></div>
                </div>
                <div class="summary-row">
                    <div>Pajak (10%)</div>
                    <div><?php echo formatRupiah(calculateTax()); ?></div>
                </div>
                <div class="summary-row total">
                    <div>Total</div>
                    <div><?php echo formatRupiah(calculateGrandTotal()); ?></div>
                </div>
            </div>
        </section>

        <section class="order-section">
            <h2><i class="bi bi-person-fill me-2"></i>Informasi Pemesanan</h2>

            <form method="post" action="" class="checkout-form">
                <div class="form-group">
                    <label for="customer_name"><i class="bi bi-person me-1"></i>Nama</label>
                    <input type="text" id="customer_name" name="customer_name" required
                        placeholder="Masukkan nama Anda">
                </div>

                <div class="form-group">
                    <label for="table_number"><i class="bi bi-table me-1"></i>Nomor Meja</label>
                    <select id="table_number" name="table_number" required>
                        <option value="">Pilih Nomor Meja</option>
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                            <option value="<?php echo $i; ?>">Meja <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes"><i class="bi bi-chat-text me-1"></i>Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="3"
                        placeholder="Catatan khusus untuk pesanan Anda (opsional)"></textarea>
                </div>

                <div class="form-group">
                    <label><i class="bi bi-credit-card me-1"></i>Metode Pembayaran</label>
                    <div class="payment-methods">
                        <div class="payment-method" data-method="cash">
                            <div class="payment-icon">💵</div>
                            <div>Tunai</div>
                        </div>
                        <div class="payment-method" data-method="debit">
                            <div class="payment-icon">💳</div>
                            <div>Kartu Debit</div>
                        </div>
                        <div class="payment-method" data-method="qris">
                            <div class="payment-icon">📱</div>
                            <div>QRIS</div>
                        </div>
                        <div class="payment-method" data-method="ewallet">
                            <div class="payment-icon">📲</div>
                            <div>E-Wallet</div>
                        </div>
                    </div>
                    <input type="hidden" id="payment_method" name="payment_method" value="" required>
                </div>

                <button type="submit" name="process_order" class="submit-order">
                    <i class="bi bi-check-circle me-2"></i>Proses Pesanan
                </button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kopi Nuri. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="js/order_details.js"></script>
</body>

</html>