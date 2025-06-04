<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';


$id_pesanan = $_GET['id_pesanan'];
$query_detail = "SELECT p.id_pesanan, p.nama, p.nomor_meja, p.total_harga, p.tanggal_pesan, p.metode_pembayaran, p.bukti_pembayaran, dp.kode_menu, dp.jumlah, dp.subtotal
FROM tabel_pesan p
    JOIN tabel_detail_pesan dp ON p.id_pesanan = dp.id_pesanan
WHERE
    p.id_pesanan = '$id_pesanan'";
var_dump($query_detail);
$result_detail = mysqli_query($connection, $query_detail);
$pesanan = mysqli_fetch_assoc($result_detail);
var_dump($pesanan);
if (!$result_detail) {
    die("Query detail pesanan gagal: " . mysqli_error($conn));
}

?>

<section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>Detail Pesanan </h1>
        <a href="./index.php" class="btn btn-light">Kembali</a>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- isi dari detail pemesanan -->
                    <div class="col-lg-6 mb-4">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Pesanan
                        </h5>
                        <div class="order-info">
                            <?php while ($order_data = $pesanan) ?>
                            <div class="info-item">
                                <span><i class="bi bi-hash me-2"></i>ID Pesanan:</span>
                                <strong><?php echo $order_data['id_pesanan']; ?></strong>
                            </div>
                            <div class="info-item">
                                <span><i class="bi bi-person me-2"></i>Nama Pelanggan:</span>
                                <strong><?php echo htmlspecialchars($order_data['nama']); ?></strong>
                            </div>
                            <div class="info-item">
                                <span><i class="bi bi-table me-2"></i>Nomor Meja:</span>
                                <strong>Meja <?php echo $order_data['nomor_meja']; ?></strong>
                            </div>
                            <div class="info-item">
                                <span><i class="bi bi-calendar me-2"></i>Tanggal Pesan:</span>
                                <strong><?php echo date('d/m/Y H:i', strtotime($order_data['tanggal_pesan'])); ?></strong>
                            </div>
                            <div class="info-item">
                                <span><i class="bi bi-credit-card me-2"></i>Metode Pembayaran:</span>
                                <strong class="text-uppercase"><?php echo $order_data['metode_pembayaran']; ?></strong>
                            </div>
                            <?php if (!empty($order_data['catatan'])): ?>
                                <div class="info-item">
                                    <span><i class="bi bi-chat-text me-2"></i>Catatan:</span>
                                    <em>"<?php echo htmlspecialchars($order_data['catatan']); ?>"</em>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<?php
require_once '../layout/_bottom.php';
?>