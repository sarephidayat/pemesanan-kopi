<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$username = $_GET['username'];
$query = mysqli_query($connection, "SELECT * FROM tabel_admin WHERE username='$username'");

?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>Ubah Password</h1>
    <a href="./index.php" class="btn btn-light">Kembali</a>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <!-- // Form -->
          <form action="./update-password.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="username" value="<?= $row['username'] ?>">
            <table cellpadding="8" class="w-100">
              <!-- Nama Lengkap -->
              <tr>
                <td>Password Lama</td>
                <td><input class="form-control" type="text" name="password-lama" size="20" required>
                </td>
              </tr>
              <!-- Email -->
              <tr>
                <td>Password Baru</td>
                <td><input class="form-control" type="text" name="password-baru" size="20" required></td>
              </tr>
              <!-- Username -->
              <tr>
                <td>Konfirmasi Password</td>
                <td><input class="form-control" type="password" name="password-confirm" size="20" required></td>
              </tr>

              <tr>
                <td>
                  <input class="btn btn-primary d-inline" type="submit" name="proses" value="Ubah">
                  <a href="./index.php" class="btn btn-danger ml-1">Batal</a>
                <td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
</section>

<?php
require_once '../layout/_bottom.php';
?>