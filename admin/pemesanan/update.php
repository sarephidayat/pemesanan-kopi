<?php
session_start();
require_once '../helper/connection.php';

$kode_menu = $_POST['kode_menu'];
$nama = $_POST['nama'];
$harga = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];
$stok = $_POST['stok'];
$username = '23080960032';
$kode_kategori = 'MKN-2156';

$image_name = null;

// Cek jika ada file gambar yang diunggah
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $file_name = $_FILES['image']['name'];
  $file_tmp = $_FILES['image']['tmp_name'];
  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
  $allowed_ext = ['jpg', 'jpeg', 'png'];

  if (in_array($file_ext, $allowed_ext)) {
    $upload_dir = "../assets/img/";
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    $new_file_name = time() . "_" . uniqid() . "." . $file_ext;
    $upload_path = $upload_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {
      $image_name = $new_file_name; // hanya simpan nama filenya, bukan path lengkap
    } else {
      $_SESSION['info'] = [
        'status' => 'failed',
        'message' => 'Gagal mengupload gambar.'
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

// Buat query update
if ($image_name) {
  // Jika gambar diunggah, update dengan gambar baru
  $query = mysqli_query($connection, "UPDATE tabel_menu 
        SET 
            nama = '$nama', 
            harga = '$harga', 
            deskripsi = '$deskripsi', 
            stok = '$stok', 
            username = '$username', 
            kode_kategori = '$kode_kategori',
            image = '$image_name'
        WHERE 
            kode_menu = '$kode_menu'");
} else {
  // Jika tidak ada gambar baru, jangan ubah kolom image
  $query = mysqli_query($connection, "UPDATE tabel_menu 
        SET 
            nama = '$nama', 
            harga = '$harga', 
            deskripsi = '$deskripsi', 
            stok = '$stok', 
            username = '$username', 
            kode_kategori = '$kode_kategori'
        WHERE 
            kode_menu = '$kode_menu'");
}

// Beri feedback ke user
if ($query) {
  $_SESSION['info'] = [
    'status' => 'success',
    'message' => 'Berhasil mengubah data'
  ];
} else {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => mysqli_error($connection)
  ];
}
header('Location: ./index.php');
