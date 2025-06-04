<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$id_pesanan = $_GET['id_pesanan'];

// Query untuk mengambil semua detail pesanan
$query_detail = "SELECT p.id_pesanan, p.nama AS nama_pelanggan, p.nomor_meja, p.catatan, p.total_harga, p.tanggal_pesan, p.metode_pembayaran, p.bukti_pembayaran, dp.kode_menu, dp.jumlah, dp.subtotal, m.nama AS nama_menu
FROM tabel_pesan p
    JOIN tabel_detail_pesan dp ON p.id_pesanan = dp.id_pesanan
    JOIN tabel_menu m ON dp.kode_menu = m.kode_menu
WHERE p.id_pesanan = '$id_pesanan'";

$result_detail = mysqli_query($connection, $query_detail);

if (!$result_detail) {
    die("Query detail pesanan gagal: " . mysqli_error($connection));
}

// Ambil data pesanan (header info) dari baris pertama
$first_row = mysqli_fetch_assoc($result_detail);
if (!$first_row) {
    die("Data pesanan tidak ditemukan");
}

// Reset pointer untuk mengambil semua items
mysqli_data_seek($result_detail, 0);

// Simpan data header pesanan
$pesanan_header = [
    'id_pesanan' => $first_row['id_pesanan'],
    'nama_pelanggan' => $first_row['nama_pelanggan'],
    'nomor_meja' => $first_row['nomor_meja'],
    'catatan' => $first_row['catatan'],
    'total_harga' => $first_row['total_harga'],
    'tanggal_pesan' => $first_row['tanggal_pesan'],
    'metode_pembayaran' => $first_row['metode_pembayaran'],
    'bukti_pembayaran' => $first_row['bukti_pembayaran']
];

// Simpan semua items dalam array
$detail_items = [];
while ($row = mysqli_fetch_assoc($result_detail)) {
    $detail_items[] = [
        'kode_menu' => $row['kode_menu'],
        'nama_menu' => $row['nama_menu'],
        'jumlah' => $row['jumlah'],
        'subtotal' => $row['subtotal']
    ];
}

// // Debug - hapus setelah testing
// echo "<pre>";
// echo "Header Pesanan:\n";
// print_r($pesanan_header);
// echo "\nDetail Items:\n";
// print_r($detail_items);
// echo "</pre>";
?>

<section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>Detail Pesanan</h1>
        <a href="./index.php" class="btn btn-light">Kembali</a>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table cellpadding="8" class="w-100">
                        <h5 class="mt-3 mb-3">Detail Pesanan</h5>
                        <tr>
                            <td>Nomor Pesanan</td>
                            <td><input class="form-control" type="text" name="id_pesanan" size="20" required
                                value="<?= $pesanan_header['id_pesanan'] ?>" disabled></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td><input class="form-control" type="text" name="nama" size="20" required 
                                value="<?= htmlspecialchars($pesanan_header['nama_pelanggan']) ?>" disabled></td>
                        </tr>
                        <tr>
                            <td>Tanggal Pesan</td>
                            <td><input class="form-control" type="datetime-local" name="tanggal_pesan" required
                                value="<?= date('Y-m-d\TH:i', strtotime($pesanan_header['tanggal_pesan'])) ?>" disabled></td>
                        </tr>
                        <tr>
                            <td>Nomor Meja</td>
                            <td><input class="form-control" type="number" name="nomor_meja" size="20" required
                                value="<?= $pesanan_header['nomor_meja'] ?>" disabled></td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td><textarea class="form-control" name="catatan" rows="3" disabled><?= htmlspecialchars($pesanan_header['catatan']) ?></textarea></td>
                        </tr>
                        
                        <!-- ITEM PESANAN - SECTION BARU -->
                        <tr>
                            <td colspan="2">
                                <h5 class="mt-3 mb-3">Item Pesanan</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Menu</th>
                                                <th>Nama Menu</th>
                                                <th>Jumlah</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($detail_items)): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <em>Tidak ada item pesanan</em>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $no = 1; ?>
                                                <?php foreach ($detail_items as $item): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $no++ ?></td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary"><?= htmlspecialchars($item['kode_menu']) ?></span>
                                                        </td>
                                                        <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning text-dark"><?= $item['jumlah'] ?></span>
                                                        </td>
                                                        <td class="text-end">
                                                            Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                
                                                <!-- Total Row -->
                                                <tr class="table-success">
                                                    <td colspan="4" class="text-end"><strong>TOTAL KESELURUHAN:</strong></td>
                                                    <td class="text-end">
                                                        <strong class="fs-5">Rp <?= number_format($pesanan_header['total_harga'], 0, ',', '.') ?></strong>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Metode Pembayaran</td>
                            <td>
                                <select class="form-control" name="metode_pembayaran" required disabled>
                                    <option value="cash" <?= ($pesanan_header['metode_pembayaran'] == 'tunai') ? 'selected' : '' ?>>Tunai</option>
                                    <option value="qris" <?= ($pesanan_header['metode_pembayaran'] == 'qris') ? 'selected' : '' ?>>QRIS</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Bukti Pembayaran</td>
                            <td>
                                <?php if (!empty($pesanan_header['bukti_pembayaran'])): ?>
                                    <img src="../../User/uploads/bukti-pembayaran/<?= htmlspecialchars($pesanan_header['bukti_pembayaran']) ?>" 
                                         alt="Bukti Pembayaran" 
                                         style="width: 150px; height: auto; border: 1px solid #ddd; border-radius: 5px; cursor: pointer;"
                                         onclick="window.open(this.src, '_blank')">
                                    <br>
                                    <small class="text-muted">Klik untuk memperbesar</small>
                                <?php else: ?>
                                    <em class="text-muted">Belum ada bukti pembayaran</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.badge {
    font-size: 0.85em;
}

.table th, .table td {
    vertical-align: middle;
}

img[onclick] {
    transition: transform 0.2s;
}

img[onclick]:hover {
    transform: scale(1.05);
}
</style>

<?php
require_once '../layout/_bottom.php';
?>