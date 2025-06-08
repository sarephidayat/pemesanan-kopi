<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$result = mysqli_query($connection, "SELECT * FROM tabel_pesan");

function formatRupiah($angka)
{
  return 'Rp' . number_format($angka, 0, ',', '.');
}

?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>List Pemesanan</h1>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped w-100" id="table-1">
              <thead>
                <tr>
                  <th>Id Pesanan</th>
                  <th>Nama</th>
                  <th>Total Harga</th>
                  <th style="width: 10px;">Nomor Meja</th>
                  <th>Tanggal Pesan</th>
                  <th style="width: 10px;">Metode Pembayaran</th>
                  <th>Status</th>
                  <th>Bukti Pembayaran</th>
                  <th style="width: 150">Order Detail</th>
                  <th style="width: 150">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($data = mysqli_fetch_array($result)):
                  ?>

                  <tr>
                    <td><?= $data['id_pesanan'] ?></td>
                    <td><?= $data['nama'] ?></td>
                    <td><?= formatRupiah($data['total_harga']) ?></td>
                    <td><?= $data['nomor_meja'] ?></td>
                    <td><?= $data['tanggal_pesan'] ?></td>
                    <td><?= $data['metode_pembayaran'] ?></td>
                    <?php if ($data['status'] == 'pending'): ?>
                      <td><span class="badge badge-warning"><?= $data['status'] ?></span></td>
                    <?php elseif ($data['status'] == 'dibatalkan'): ?>
                      <td><span class="badge badge-danger"><?= $data['status'] ?></span></td>
                    <?php elseif ($data['status'] == 'selesai'): ?>
                      <td><span class="badge badge-success"><?= $data['status'] ?></span></td>
                    <?php else: ?>
                      <td><span class="badge badge-primary"><?= $data['status'] ?></span></td>
                    <?php endif; ?>
                    <td><img style="width: 100px; height: auto;"
                        src="../../user/uploads/bukti-pembayaran/<?= $data['bukti_pembayaran'] ?>"
                        alt="<?= $data['bukti_pembayaran'] ?>"></td>
                    <td>
                      <a class="btn btn-sm btn-info mb-md-0 mb-1"
                        href="detail_pemesanan.php?id_pesanan=<?= $data['id_pesanan'] ?>">Detail
                      </a>
                    </td>
                    <td>
                      <a class="btn btn-sm btn-danger mb-md-0 mb-1"
                        href="delete.php?id_pesanan=<?= $data['id_pesanan'] ?>">Batalkan
                      </a>
                      <a class="btn btn-sm btn-success mb-md-0 mb-1"
                        href="finish_order.php?id_pesanan=<?= $data['id_pesanan'] ?>">Selesai
                      </a>
                    </td>
                  </tr>

                  <?php
                endwhile;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</section>

<?php
require_once '../layout/_bottom.php';
?>
<!-- Page Specific JS File -->
<?php
if (isset($_SESSION['info'])):
  if ($_SESSION['info']['status'] == 'success') {
    ?>
    <script>
      iziToast.success({
        title: 'Sukses',
        message: `<?= $_SESSION['info']['message'] ?>`,
        position: 'topCenter',
        timeout: 5000
      });
    </script>
    <?php
  } else {
    ?>
    <script>
      iziToast.error({
        title: 'Gagal',
        message: `<?= $_SESSION['info']['message'] ?>`,
        timeout: 5000,
        position: 'topCenter'
      });
    </script>
    <?php
  }

  unset($_SESSION['info']);
  $_SESSION['info'] = null;
endif;
?>
<script src="../assets/js/page/modules-datatables.js"></script>