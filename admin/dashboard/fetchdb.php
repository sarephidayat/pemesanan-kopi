<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$functionName = $_GET['functionName'];
switch ($functionName) {
    case 'getDataMenu':
        getDataMenu();
        break;
    case 'getMostMenu':
        getMostMenu();
        break;
    case 'getLeastMenu':
        getLeastMenu();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid function name']);
        break;
}

function getDataMenu()
{
    $data = [];
    global $koneksi;
    $query = "SELECT * FROM tabel_menu";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'kode_menu' => $row['kode_menu'],
            'nama' => $row['nama'],
            'harga' => $row['harga'],
            'deskripsi' => $row['deskripsi'],
            'stok' => $row['stok'],
            'image' => $row['image']
        ];
    }
    echo json_encode($data);
}
function getMostMenu()
{
    $data = [];
    global $koneksi;

    $query = "SELECT * FROM tabel_menu ORDER BY stok DESC LIMIT 5";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'kode_menu' => $row['kode_menu'],
            'nama' => $row['nama'],
            'harga' => $row['harga'],
            'deskripsi' => $row['deskripsi'],
            'stok' => $row['stok'],
            'image' => $row['image']
        ];
    }

    echo json_encode($data);
}

function getLeastMenu()
{
    $data = [];
    global $koneksi;

    $query = "SELECT * FROM tabel_menu ORDER BY stok ASC LIMIT 5";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'kode_menu' => $row['kode_menu'],
            'nama' => $row['nama'],
            'harga' => $row['harga'],
            'deskripsi' => $row['deskripsi'],
            'stok' => $row['stok'],
            'image' => $row['image']
        ];
    }

    echo json_encode($data);
}


