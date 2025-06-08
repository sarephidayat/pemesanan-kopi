<?php
session_start();
require_once '../helper/connection.php';

$id_pelanggan = $_POST['id_pelanggan'];
$username = $_POST['username'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];

// Buat query update

// Jika gambar diunggah, update dengan gambar baru
$query = mysqli_query($connection, "UPDATE tabel_pelanggan 
        SET 
            id_pelanggan = '$id_pelanggan',
            username = '$username',
            nama = '$nama', 
            email = '$email',
            password = '$password'
        WHERE 
            id_pelanggan = '$id_pelanggan'");


// Beri feedback ke user
if ($query) {
  $_SESSION['info'] = [
    'status' => 'success',
    'message' => 'Data pelanggan berhasil diubah.'
  ];
} else {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => mysqli_error($connection)
  ];
}
header('Location: ./index.php');
