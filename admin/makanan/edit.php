<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$kode_menu = $_GET['kode_menu'];
$query = mysqli_query($connection, "SELECT * FROM tabel_menu WHERE kode_menu='$kode_menu'");
?>

<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>Ubah Data Dosen</h1>
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
              <input type="hidden" name="kode_menu" value="<?= $row['kode_menu'] ?>">
              <table cellpadding="8" class="w-100">
                <tr>
                  <td>Kode Menu</td>
                  <td><input class="form-control" type="text" name="kode_menu" size="20" required
                      value="<?= $row['kode_menu'] ?>" disabled></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td><input class="form-control" type="text" name="nama" size="20" required value="<?= $row['nama'] ?>">
                  </td>
                </tr>

                <tr>
                  <td>Harga</td>
                  <td colspan="3"><textarea class="form-control" name="harga" id="harga"
                      required><?= $row['harga'] ?></textarea></td>
                </tr>

                <tr>
                  <td>Deskripsi</td>
                  <td><input class="form-control" type="text" name="deskripsi" size="20" required
                      value="<?= $row['deskripsi'] ?>">
                  </td>
                </tr>

                <tr>
                  <td>Stok</td>
                  <td><input class="form-control" type="number" name="stok" size="20" required
                      value="<?= $row['stok'] ?>">
                  </td>
                </tr>

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