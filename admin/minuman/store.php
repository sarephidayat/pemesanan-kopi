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
  $kode_kategori = 'MNM-3821';

  $image_name = null;

  if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png'];

    if (in_array($file_ext, $allowed_ext)) {
      $upload_dir = "../assets/img/";
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      $image_name = time() . "_" . uniqid() . "." . $file_ext;
      $upload_path = $upload_dir . $image_name;

      if (!move_uploaded_file($file_tmp, $upload_path)) {
        $_SESSION['info'] = [
          'status' => 'failed',
          'message' => 'Gagal memindahkan file gambar ke folder tujuan.'
        ];
        header('Location: ./index.php');
        exit;
      }
    } else {
      $_SESSION['info'] = [
        'status' => 'failed',
        'message' => 'Hanya file JPG, JPEG, dan PNG yang diizinkan.'
      ];
      header('Location: ./index.php');
      exit;
    }
  }

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

