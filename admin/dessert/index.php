<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$result = mysqli_query($connection, "SELECT * FROM tabel_menu WHERE kode_kategori='DST-7294'");
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>List Menu Dessert</h1>
    <a href="./create.php" class="btn btn-primary custom-login-btn" style="background-color: #5c3d2e;">Tambah Data</a>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped w-100" id="table-1">
              <thead>
                <tr>
                  <th>Kode Menu</th>
                  <th>Nama</th>
                  <th>Harga</th>
                  <th>Deskripsi</th>
                  <th>Stok</th>
                  <th>Gambar</th>
                  <th style="width: 150">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($data = mysqli_fetch_array($result)):
                  ?>

                  <tr>
                    <td><?= $data['kode_menu'] ?></td>
                    <td><?= $data['nama'] ?></td>
                    <td><?= $data['harga'] ?></td>
                    <td><?= $data['deskripsi'] ?></td>
                    <td><?= $data['stok'] ?></td>
                    <td><img style="width: 100px; height: 100px;" src="../assets/img/<?= $data['image'] ?>"
                        alt="<?= $data['image'] ?>"></td>
                    <td>
                      <a class="btn btn-sm btn-danger mb-md-0 mb-1" href="delete.php?kode_menu=<?= $data['kode_menu'] ?>">
                        <i class="fas fa-trash fa-fw"></i>
                      </a>
                      <a class="btn btn-sm btn-info" href="edit.php?kode_menu=<?= $data['kode_menu'] ?>">
                        <i class="fas fa-edit fa-fw"></i>
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