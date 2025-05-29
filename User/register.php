<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Mengecek apakah email sudah terdaftar
  $query = "SELECT id_pelanggan FROM tabel_pelanggan WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $error = "Email sudah terdaftar!";
  } else {
    // Simpan ke database
    $query = "INSERT INTO tabel_pelanggan (nama, email, password) 
              VALUES ('$nama', '$email', '$password')";

    if (mysqli_query($conn, $query)) {
      $success = "Pendaftaran berhasil! Anda akan dialihkan ke halaman login.";
      header("Refresh: 3; url=login.php"); // Redirect setelah 3 detik
    } else {
      $error = "Pendaftaran gagal: " . mysqli_error($conn);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Discounted Labs</title>
  <link rel="stylesheet" href="assets/css/style-LoginRegister.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container1">
    <!-- Left Side - Illustration -->
    <div class="left-side">
      <div class="logo">
        Cafe Kopi Nuri
      </div>

      <div class="illustration">
        <div class="doctor-container1">
          <div class="doctor-bg"></div>
          <div class="doctor-avatar">🍵</div>

          <div class="floating-elements">
            <div class="floating-pill pill-1"></div>
            <div class="floating-pill pill-2"></div>
            <div class="floating-pill pill-3"></div>
            <div class="floating-pill pill-4"></div>
          </div>
        </div>
      </div>

      <div class="copyright">
        <p>&copy; 2025 Kopi Nuri. Semua hak dilindungi.</p>
      </div>
    </div>

    <!-- Right Side - Form -->
    <div class="right-side">
      <div class="form-container1">
        <div class="tabs">
          <div class="tab active">Daftar</div>
          <div class="tab">Masuk</div>
        </div>

        <form method="post" id="authForm">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3" role="alert">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>
          <?php if (isset($success)): ?>
            <div class="alert alert-success mt-3" role="alert">
              <?php echo $success; ?>
            </div>
          <?php endif; ?>
          <div id="signupForm">
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" id="nama" name="nama" placeholder="Kopi Nuri" required>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" placeholder="cafekopinuri@nuri.ac.id" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="" required>
            </div>

            <button type="submit" name="submit" class="submit-btn">Daftar</button>

            <div class="login-link">
              Sudah Mempunyai Akun? <a href="login.php">Masuk</a>
            </div>
          </div>
        </form>

        <div class="social-icons">
          <a href="https://www.instagram.com/kopinuriindonesia/" target="_blank" class="social-icon"><i
              class="bi bi-instagram"></i> </a>
          <a href="https://wa.me/6285212345678?text=Halo%20saya%20tertarik%20dengan%20produk%20Anda" class="social-icon"
            target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>

        <div class="contact-info">
          <span>📞 (+62)89529684820</span>
          <span>✉️ cafekopinuri@nuri.ac.id</span>
        </div>
      </div>
    </div>
  </div>
  <script src="js/register.js"></script>
</body>

</html>