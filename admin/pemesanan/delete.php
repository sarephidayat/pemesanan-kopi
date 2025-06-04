<?php
session_start();
require_once '../helper/connection.php';

$kode_menu = $_GET['kode_menu'];

$result = mysqli_query($connection, "DELETE FROM tabel_menu WHERE kode_menu='$kode_menu'");

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
