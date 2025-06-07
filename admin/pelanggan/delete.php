<?php
session_start();
require_once '../helper/connection.php';

$id_pelanggan = $_GET['id_pelanggan'];

//tambah kan kondisi ketika pelanggan masih memiliki transaksi
//code...

$result = mysqli_query($connection, "DELETE FROM tabel_pelanggan WHERE id_pelanggan='$id_pelanggan'");

if (mysqli_affected_rows($connection) > 0) {
  $_SESSION['info'] = [
    'status' => 'success',
    'message' => 'Berhasil menghapus data'
  ];
  header('Location: ./index.php');
} else {
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => mysqli_error($connection)
  ];
  header('Location: ./index.php');
}
