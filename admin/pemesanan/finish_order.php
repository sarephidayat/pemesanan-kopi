<?php
session_start();
require_once '../helper/connection.php';

$id_pesanan = $_GET['id_pesanan'];

// Update status pesanan menjadi selesai
$result = mysqli_query($connection, "UPDATE tabel_pesan SET status = 'selesai' WHERE id_pesanan = '$id_pesanan'");

if (mysqli_affected_rows($connection) > 0) {
  // Ambil semua detail pesanan untuk pesanan ini
  $query_detail = mysqli_query($connection, "SELECT kode_menu, jumlah FROM tabel_detail_pesan WHERE id_pesanan = '$id_pesanan'");

  while ($row = mysqli_fetch_assoc($query_detail)) {
    $kode_menu = $row['kode_menu'];
    $jumlah = $row['jumlah'];

    // Kurangi stok dari tabel_menu
    mysqli_query($connection, "UPDATE tabel_menu SET stok = stok - $jumlah WHERE kode_menu = '$kode_menu'");
  }

  // Set notifikasi berhasil
  $_SESSION['info'] = [
    'status' => 'success',
    'message' => 'Status pesanan selesai dan stok berhasil diperbarui.'
  ];
  header('Location: ./index.php');
} else {
  // Jika gagal update status
  $_SESSION['info'] = [
    'status' => 'failed',
    'message' => 'Gagal mengubah status pesanan: ' . mysqli_error($connection)
  ];
  header('Location: ./index.php');
}
