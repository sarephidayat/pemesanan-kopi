<?php
session_start();
require_once '../helper/connection.php';

$username = $_SESSION['username'];
$password_lama = trim($_POST['password-lama']);
$password_baru = trim($_POST['password-baru']);
$password_confirm = trim($_POST['password-confirm']);


// Cek konfirmasi password
if ($password_baru !== $password_confirm) {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'Konfirmasi password tidak sesuai'
  ];
  header('Location: ./index.php');
  exit;
}

// Mengambil password dari database
$query_user = mysqli_query($connection, "SELECT password FROM tabel_admin WHERE username = '$username'");
$data_user = mysqli_fetch_assoc($query_user);

// Cek apakah user ditemukan dan password lama benar
if (!$data_user) {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'User tidak ditemukan'
  ];
  header('Location: ./index.php');
  exit;
}

// Verifikasi password lama
if ($password_lama !== $data_user['password']) {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'Password lama salah'
  ];
  header('Location: ./index.php');
  exit;
}

// Cek apakah password baru sama dengan password lama
if ($password_lama === $password_baru) {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'Password baru tidak boleh sama dengan password lama'
  ];
  header('Location: ./index.php');
  exit;
}

// Update password baru
$query_update = mysqli_query($connection, "UPDATE tabel_admin SET password = '$password_baru' WHERE username = '$username'");

// Feedback ke user
if ($query_update && mysqli_affected_rows($connection) > 0) {
  $_SESSION['info'] = [
    'status' => 'success',
    'message' => 'Berhasil mengubah password'
  ];
} else {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'Gagal mengubah password: ' . mysqli_error($connection)
  ];
}

header('Location: ./index.php');
exit;
?>