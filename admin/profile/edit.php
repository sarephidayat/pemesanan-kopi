<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$username = $_GET['username'];
$query = mysqli_query($connection, "SELECT * FROM tabel_admin WHERE username='$username'");
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>Ubah Profile</h1>
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
              <input type="hidden" name="username" value="<?= $row['username'] ?>">
              <table cellpadding="8" class="w-100">
                <!-- Nama Lengkap -->
                <tr>
                  <td>Nama Lengkap</td>
                  <td><input class="form-control" type="text" name="nama" size="20" required value="<?= $row['nama'] ?>">
                  </td>
                </tr>
                <!-- Email -->
                <tr>
                  <td>Email</td>
                  <td><input class="form-control" type="text" name="email" size="20" required
                      value="<?= $row['email'] ?>"></td>
                </tr>
                <!-- Username -->
                <tr>
                  <td>Username</td>
                  <td><input class="form-control" type="text" name="username" size="20" required
                      value="<?= $row['username'] ?>"></td>
                </tr>
                <!-- gambar -->
                <tr>
                  <td>Gambar</td>
                  <td>
                    <?php if ($row['image']): ?>
                      <img src="../assets/img/<?php echo htmlspecialchars($row['image']); ?>" alt="Foto Makanan"
                        style="width: 100px; height: auto;">
                    <?php endif; ?>
                    <input class="form-control mt-2" type="file" name="image">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
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