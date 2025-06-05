<?php
require_once 'admin/helper/connection.php';
session_start();
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Cek di tabel_admin
  $sql_admin = "SELECT * FROM tabel_admin WHERE username='$username'";
  $result_admin = mysqli_query($connection, $sql_admin);
  $row_admin = mysqli_fetch_assoc($result_admin);

  if (mysqli_num_rows($result_admin) === 1 && $password == $row_admin['password']) {
    $_SESSION['login'] = $row_admin;
    $_SESSION['role'] = 'admin';
    header('Location: admin/index.php');
    exit;
  }

  // Jika tidak ditemukan di admin, cek di tabel_pengguna
  $sql_pengguna = "SELECT * FROM tabel_pelanggan WHERE username='$username'";
  $result_pengguna = mysqli_query($connection, $sql_pengguna);
  $row_pengguna = mysqli_fetch_assoc($result_pengguna);

  if (mysqli_num_rows($result_pengguna) === 1 && $password == $row_pengguna['password']) {
    $_SESSION['login'] = $row_pengguna;
    $_SESSION['role'] = 'pelanggan';
    header('Location: user/index.php');
    exit;
  }

  // Jika keduanya tidak cocok
  $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; UIN WS</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="admin/assets/modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="admin/assets/css/style.css">
  <link rel="stylesheet" href="admin/assets/css/components.css">
  <style>
    .custom-login-btn:hover {
      background-color: #6B4226 !important;
      /* warna cokelat saat hover */
    }
  </style>


</head>

<body style="background-color: #F5E9DA;
  background-position: center;
  background-repeat: no-repeat;">
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="admin/assets/img/logo-removebg.png" alt="logo" width="120">
            </div>

            <div class="card card-primary" style="
                border-top: 2px solid #4B2E1C;
                background-color: rgba(255, 255, 255, 0.5);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            ">
              <div class="card-header">
                <h4 style="color: #4B2E1C;">Login</h4>
              </div>

              <div class="card-body">
                <form method="POST" action="" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" tabindex="1" required
                      autofocus
                      style="border: 1px solid rgba(75, 46, 28, 0.1); box-shadow: 0 0px 1px rgba(75, 46, 28, 0.3);">
                    <div class="invalid-feedback">
                      Mohon isi username
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2"
                      style="border: 1px solid rgba(75, 46, 28, 0.1); box-shadow: 0 0px 1px rgba(75, 46, 28, 0.3);"
                      required>
                    <div class="invalid-feedback">
                      Mohon isi kata sandi
                    </div>
                  </div>

                  <!-- <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">Ingat Saya</label>
                    </div>
                  </div> -->

                  <div class="form-group">
                    <button name="submit" type="submit" class="btn btn-primary btn-lg btn-block custom-login-btn"
                      style="background-color: #4B2E1C;" tabindex="3">
                      Login
                    </button>
                  </div>
                  <div class="login-link" style="text-align: center;">
                    Belum mempunyai akun? <a href="register.php">Register</a>
                  </div>

                </form>
                <?php
                // var_dump($error);
                if (isset($error)) {
                  echo "<p class='alert alert-danger mt-4'> password/user salah </p>";
                }
                ?>
              </div>
              <div class="login-link">

              </div>
            </div>
            <div class="simple-footer">
              &copy; 2025 Kopi Nuri. Semua hak dilindungi.
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
</body>

</html>