<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$id_pelanggan = $_GET['id_pelanggan'];
$query = mysqli_query($connection, "SELECT * FROM tabel_pelanggan WHERE id_pelanggan='$id_pelanggan'");
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>Ubah Data Pelanggan</h1>
    <a href="./index.php" class="btn btn-light">Kembali</a>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <!-- // Form -->
          <form action="./update.php" method="post" enctype="multipart/form-data">
            <?php
            while ($row = mysqli_fetch_array($query)) {
              ?>
              <input type="hidden" name="id_pelanggan" value="<?= $row['id_pelanggan'] ?>">
              <table cellpadding="8" class="w-100">
                <tr>
                  <td>ID Pelanggan</td>
                  <td><input class="form-control" type="text" name="id_pelanggan" size="20" required
                      value="<?= $row['id_pelanggan'] ?>" disabled></td>
                </tr>
                <tr>
                  <td>Username</td>
                  <td><input class="form-control" type="text" name="username" size="20" required
                      value="<?= $row['username'] ?>">
                  </td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td><input class="form-control" type="text" name="nama" size="20" required value="<?= $row['nama'] ?>">
                  </td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><input class="form-control" type="email" name="email" size="20" required
                      value="<?= $row['email'] ?>">
                  </td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><input class="form-control" type="text" name="password" size="20" required
                      value="<?= $row['password'] ?>">
                  </td>
                </tr>

                <tr>
                  <td>
                    <input class="btn btn-primary d-inline" type="submit" name="proses" value="Ubah">
                    <a href="./index.php" class="btn btn-danger ml-1">Batal</a>
                  <td>
                </tr>
              </table>

            <?php } ?>
          </form>
        </div>
      </div>
    </div>
</section>

<?php
require_once '../layout/_bottom.php';
?>