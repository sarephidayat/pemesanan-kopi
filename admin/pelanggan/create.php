<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>Tambah Pelanggan</h1>
    <a href="./index.php" class="btn btn-primary custom-login-btn" style="background-color: #5c3d2e;">Kembali</a>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <!-- // Form -->
          <form action="./store.php" method="POST" enctype="multipart/form-data">
            <table cellpadding="8" class="w-100">

              <tr>
                <td>ID Pelanggan</td>
                <td><input class="form-control" type="number" name="id" size="20" required></td>
              </tr>

              <tr>
                <td>Nama Pelanggan</td>
                <td><input class="form-control" type="text" name="nama" size="20" required></td>
              </tr>

              <tr>
                <td>Username</td>
                <td><input class="form-control" type="text" name="username" size="20" required></td>
              </tr>

              <tr>
                <td>Email</td>
                <td><input class="form-control" type="email" name="email" size="20" required></td>
              </tr>

              <tr>
                <td>Password</td>
                <td><input class="form-control" type="password" name="password" size="20" required></td>
              </tr>

              <tr>
                <td>
                  <input class="btn btn-primary" type="submit" name="proses" value="Simpan">
                  <input class="btn btn-danger" type="reset" name="batal" value="Bersihkan">
                </td>
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