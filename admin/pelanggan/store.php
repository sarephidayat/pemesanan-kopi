<?php
session_start();
require_once '../helper/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_pelanggan = $_POST['id'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  // Simpan data ke database
  $query = mysqli_query($connection, "INSERT INTO tabel_pelanggan 
        (id_pelanggan, nama, username, password, email) 
        VALUES 
        ('$id_pelanggan', '$nama', '$username', '$password', '$email')");

  if ($query) {
    $_SESSION['info'] = [
      'status' => 'success',
      'message' => 'Berhasil menambah data'
    ];
  } else {
    $_SESSION['info'] = [
      'status' => 'failed',
      'message' => 'Gagal menyimpan ke database: ' . mysqli_error($connection)
    ];
  }

  header('Location: ./index.php');
  exit;
}

