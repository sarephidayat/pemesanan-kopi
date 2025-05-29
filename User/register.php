<?php
// koneksi
$conn = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Simpan data ke database atau lakukan proses lainnya
  $query = "INSERT INTO tabel_pelanggan (nama, email, password) VALUES ('$nama', '$email', '$password')";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Discounted Labs</title>
  <link rel="stylesheet" href="assets/css/style-login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <!-- Left Side - Illustration -->
    <div class="left-side">
      <div class="logo">
        Cafe Kopi Nuri
      </div>

      <div class="illustration">
        <div class="doctor-container">
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
      <div class="form-container">
        <div class="tabs">
          <div class="tab active">Daftar</div>
          <div class="tab">Masuk</div>
        </div>

        <form id="authForm">
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
              <input type="password" id="password" name="password" placeholder="••••••••" required>
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

  <script>
    function switchTab(tab) {
      const signupTab = document.querySelector('.tab:first-child');
      const signinTab = document.querySelector('.tab:last-child');
      const signupForm = document.getElementById('signupForm');
      const signinForm = document.getElementById('signinForm');

      if (tab === 'signup') {
        signupTab.classList.add('active');
        signinTab.classList.remove('active');
        signupForm.style.display = 'block';
        signinForm.style.display = 'none';
      } else {
        signupTab.classList.remove('active');
        signinTab.classList.add('active');
        signupForm.style.display = 'none';
        signinForm.style.display = 'block';
      }
    }

    document.getElementById('authForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const activeTab = document.querySelector('.tab.active').textContent.trim();

      if (activeTab === 'Sign Up') {
        const fullName = document.getElementById('fullName').value;
        const email = document.getElementById('email').value;

        if (fullName && email) {
          alert(`Welcome ${fullName}! Your account has been created successfully.`);
          this.reset();
        }
      } else {
        const email = document.getElementById('loginEmail').value;

        if (email) {
          alert(`Welcome back! You have been signed in successfully.`);
          this.reset();
        }
      }
    });

    // Add entrance animation
    window.addEventListener('load', function () {
      const leftSide = document.querySelector('.left-side');
      const rightSide = document.querySelector('.right-side');

      leftSide.style.transform = 'translateX(-100%)';
      rightSide.style.transform = 'translateX(100%)';

      setTimeout(() => {
        leftSide.style.transition = 'transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        rightSide.style.transition = 'transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        leftSide.style.transform = 'translateX(0)';
        rightSide.style.transform = 'translateX(0)';
      }, 100);
    });
  </script>
</body>

</html>