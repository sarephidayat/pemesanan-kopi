<?php
// api/menu.php
header('Content-Type: application/json');
require_once __DIR__ . '/../shared/database.php';

// Inisialisasi koneksi
$db = new Database();
$koneksi = $db->getConnection();

$kategori = $_GET['category'] ?? "";

try {
    if ($kategori) {
        $stmt = $koneksi->prepare("SELECT * FROM tabel_menu WHERE kode_kategori = :kategori");
        $stmt->bindParam(':kategori', $kategori);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Jika tidak ada kategori, tampilkan semua
        $query = $koneksi->query("SELECT * FROM tabel_menu");
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}