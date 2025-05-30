<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$result = mysqli_query($connection, "SELECT * FROM tabel_pelanggan");
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>List Dosen</h1>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-striped w-100" id="table-1">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Pelanggan</th>
                  <th>Nama pelanggan</th>
                  <th>Email</th>
                  <th style="width: 150">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                while ($data = mysqli_fetch_array($result)):
                  ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['id_pelanggan'] ?></td>
                    <td><?= $data['nama'] ?></td>
                    <td><?= $data['email'] ?></td>
                    <td>
                      <a class="btn btn-sm btn-danger mb-md-0 mb-1"
                        href="delete.php?id_pelanggan=<?= $data['id_pelanggan'] ?>">
                        <i class="fas fa-trash fa-fw"></i>
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