<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$result = mysqli_query($connection, "SELECT * FROM tabel_admin");
?>

<section class="section">
  <div class="section-header">
    <h1>Menu Profile</h1>
  </div>
  <div class="column">
    <div class="row">
      <?php while ($admin_data = mysqli_fetch_array($result)): ?>
        <!-- Informasi Profile -->
        <div class="col-lg-6 col-md-12 col-12">
          <div class="card">
            <div class="card-header" style="justify-content: center;">
              <h4 style="color: #5c3d2e;">Profile</h4>
            </div>
            <div class="card-body">
              <div class="text-center mb-4">
                <img src="../assets/img/<?php echo $admin_data['image'] ?>" alt="Admin Avatar" class="rounded-circle"
                  width="100" height="100">
                <h5 class="mt-3 mb-1">Administrator</h5>
                <p class="text-muted">Super Admin</p>
              </div>

              <div class="row">
                <div class="col-sm-4">
                  <p class="mb-0"><strong>Nama Lengkap:</strong></p>
                </div>
                <div class="col-sm-8">
                  <p class="text-muted mb-0"><?php echo $admin_data['nama'] ?></p>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col-sm-4">
                  <p class="mb-0"><strong>Email:</strong></p>
                </div>
                <div class="col-sm-8">
                  <p class="text-muted mb-0"><?php echo $admin_data['email'] ?></p>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col-sm-4">
                  <p class="mb-0"><strong>Username:</strong></p>
                </div>
                <div class="col-sm-8">
                  <p class="text-muted mb-0"><?php echo $admin_data['username'] ?></p>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col-sm-4">
                  <p class="mb-0"><strong>Role:</strong></p>
                </div>
                <div class="col-sm-8">
                  <p class="text-muted mb-0">
                    <span class="badge badge-danger">Admin</span>
                  </p>
                </div>

              </div>
              <hr>

              <div class="row">
                <div class="col-sm-4">
                  <p class="mb-0"><strong>Last Login:</strong></p>
                </div>
                <div class="col-sm-8">
                  <p class="text-muted mb-0"><?php echo date('d F Y, H:i'); ?></p>
                </div>
              </div>
              <hr>

              <div class="text-center mt-4">
                <a href="edit.php?username=<?php echo $admin_data['username'] ?>" class="btn btn-primary">
                  <i class="fas fa-edit"></i> Edit Profile
                </a>

                <a href="ubah-password.php?username=<?php echo $admin_data['username'] ?>" class="btn btn-warning ml-2">
                  <i class="fas fa-key"></i> Ganti Password
                </a>

              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-12 col-12">
          <div class="card">
            <div class="card-header" style="justify-content: center;">
              <h4 style="color: #5c3d2e">Petunjuk Penggunaan</h4>
            </div>
            <div class="card-body">

              <!-- Bagian Edit Profil -->
              <p class="text-muted mb-2">
                Berikut langkah-langkah untuk mengubah informasi profil Anda:
              </p>
              <ol class="pl-3" style="line-height: 1.8;">
                <li>Klik tombol <strong>Edit Profil</strong> (jika tersedia).</li>
                <li>Ubah data yang ingin diperbarui, seperti nama, email, atau foto profil.</li>
                <li>Setelah selesai, tekan tombol <strong>Simpan</strong>.</li>
                <li>Logout dan login kembali jika perubahan tidak langsung terlihat.</li>
              </ol>

              <!-- Bagian Ganti Password -->
              <hr>
              <p class="text-muted mb-2">
                Langkah-langkah untuk mengganti password akun:
              </p>
              <ol class="pl-3" style="line-height: 1.8;">
                <li>Masuk ke halaman <strong>Pengaturan Akun</strong> atau <strong>Ganti Password</strong>.</li>
                <li>Masukkan password lama Anda terlebih dahulu.</li>
                <li>Ketik password baru, lalu konfirmasi ulang password tersebut.</li>
                <li>Klik tombol <strong>Simpan</strong> atau <strong>Perbarui Password</strong>.</li>
              </ol>

              <div class="alert alert-danger mt-4" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Username dan peran (role) tidak dapat diubah. Hubungi developer jika
                diperlukan.
              </div>

              <div class="text-right mt-4">
                <a href="edit.php?username=<?php echo $admin_data['username'] ?>" class="btn btn-primary">
                  <i class="fas fa-edit"></i> Edit Profile
                </a>

                <a href="ubah-password.php?username=<?php echo $admin_data['username'] ?>" class="btn btn-warning ml-2">
                  <i class="fas fa-key"></i> Ganti Password
                </a>
              </div>


            </div>
          </div>
        </div>

      </div>
    <?php endwhile; ?>
  </div>
  </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
  integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js"
  integrity="sha512-aub0tRfsNTyfYpvUs0e9G/QRsIDgKmm4x59WRkHeWUc3CXbdiMwiMQ5tTSElshZu2LCq8piM/cbIsNwuuIR4gA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/chart_stok.js"></script>
<script src="../assets/js/chart_most_menu.js"></script>
<script src="../assets/js/chart_least_menu.js"></script>


<?php
require_once '../layout/_bottom.php';
?>