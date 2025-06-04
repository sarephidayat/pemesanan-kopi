<?php
// File: order_confirmation.php
// Halaman konfirmasi pesanan setelah checkout - Dengan perbaikan tampilan mobile

session_start();

// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah ada data pesanan
if (!isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit();
}

$id_pesanan = $_SESSION['order_id'];

// Query untuk mengambil data pesanan dengan JOIN
$query_pesanan = "SELECT 
    p.id_pesanan,
    p.nama,
    p.total_harga,
    p.nomor_meja,
    p.catatan,
    p.tanggal_pesan,
    p.metode_pembayaran
FROM tabel_pesan p 
WHERE p.id_pesanan = '$id_pesanan'";

$result_pesanan = mysqli_query($conn, $query_pesanan);
if (!$result_pesanan) {
    die("Query pesanan gagal: " . mysqli_error($conn));
}

$pesanan = mysqli_fetch_assoc($result_pesanan);
if (!$pesanan) {
    die("Data pesanan tidak ditemukan!");
}

// Query untuk mengambil detail item pesanan
$query_detail = "SELECT dp.id_pesanan, dp.kode_menu, dp.jumlah, dp.subtotal, m.nama, m.harga FROM tabel_detail_pesan dp LEFT JOIN tabel_menu m ON dp.kode_menu = m.kode_menu WHERE dp.id_pesanan = '$id_pesanan'";

$result_detail = mysqli_query($conn, $query_detail);
if (!$result_detail) {
    die("Query detail pesanan gagal: " . mysqli_error($conn));
}


// Fungsi format rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan Kopi Ngelak</title>
    <link rel="stylesheet" href="assets/css/style-order-confirmation.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Kopi Ngelak</h1>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="confirmation-section">
                <div class="success-icon">✓</div>
                <h1>Pesanan Berhasil!</h1>
                <div class="order-id">Nomor Pesanan: #<?php echo $pesanan['id_pesanan']; ?></div>

                <div class="confirmation-msg">
                    Terima kasih, <?php echo htmlspecialchars($pesanan['nama']); ?>! Pesanan Anda telah kami terima dan
                    sedang diproses.
                    <br>Mohon tunggu sebentar, pesanan Anda akan segera diantar ke meja
                    <?php echo htmlspecialchars($pesanan['nomor_meja']); ?>.
                </div>

                <div class="order-details">
                    <h3>Detail Pesanan</h3>
                    <div class="detail-row">
                        <div>Tanggal & Waktu:</div>
                        <div><?php echo date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])); ?></div>
                    </div>
                    <div class="detail-row">
                        <div>Nomor Meja:</div>
                        <div><?php echo htmlspecialchars($pesanan['nomor_meja']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div>Metode Pembayaran:</div>
                        <div>
                            <?php
                            switch (strtolower($pesanan['metode_pembayaran'])) {
                                case 'cash':
                                    echo 'Tunai';
                                    break;
                                case 'transfer':
                                    echo 'Transfer Bank';
                                    break;
                                case 'e-wallet':
                                    echo 'E-Wallet';
                                    break;
                                default:
                                    echo htmlspecialchars($pesanan['metode_pembayaran']);
                            }
                            ?>
                        </div>
                    </div>
                    <?php if (!empty($pesanan['catatan'])): ?>
                        <div class="detail-row">
                            <div>Catatan:</div>
                            <div><?php echo htmlspecialchars($pesanan['catatan']); ?></div>
                        </div>
                    <?php endif; ?>

                    <h3>Item Pesanan</h3>
                    <div class="order-items">
                        <?php while ($item = mysqli_fetch_assoc($result_detail)): ?>
                            <div class="order-item">
                                <div class="item-name">
                                    <?php echo htmlspecialchars($item['nama'] ?? 'Menu tidak ditemukan'); ?>
                                    (<?php echo $item['jumlah']; ?>x)
                                </div>
                                <div class="item-details">
                                    <?php echo formatRupiah($item['subtotal']); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="order-summary">
                        <?php foreach ($result_detail as $item): ?>
                            <div class="summary-row">
                                <div>Subtotal</div>
                                <div><?php echo formatRupiah($item['subtotal']); ?></div>
                            </div>
                            <div class="summary-row">
                                <div>Pajak (10%)</div>
                                <?php $pajak = $item['subtotal'] * 0.1; ?>
                                <div><?php echo formatRupiah($pajak); ?></div>
                            </div>
                            <div class="summary-row total">
                                <div>Total</div>
                                <div><?php echo formatRupiah($item['subtotal'] + $pajak); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="print-btn" onclick="window.print()">Cetak Struk</button>
                    <a href="index.php" class="back-to-menu">Kembali ke Menu</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kafe Express. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        // Hapus session order setelah halaman dimuat
        <?php
        unset($_SESSION['order_id']);
        unset($_SESSION['order_details']);
        ?>
    </script>
</body>

</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>