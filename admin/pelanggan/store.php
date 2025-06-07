<?php
session_start();
require_once '../helper/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $kode_menu = $_POST['kode_menu'];
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $deskripsi = $_POST['deskripsi'];
  $stok = $_POST['stok'];
  $username = '23080960032';
  $kode_kategori = 'MKN-2156';

  // Simpan data ke database
  $query = mysqli_query($connection, "INSERT INTO tabel_menu 
        (kode_menu, nama, harga, deskripsi, stok, username, kode_kategori, image) 
        VALUES 
        ('$kode_menu', '$nama', '$harga', '$deskripsi', '$stok', '$username', '$kode_kategori', '$image_name')");

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

