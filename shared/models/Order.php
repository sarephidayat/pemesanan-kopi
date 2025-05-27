<?php
// shared/models/Order.php

class Order
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Membuat order baru
     * @param array $data [user_id, total, status, payment_method, address]
     * @return int ID order yang baru dibuat
     */
    public function create(array $data): int
    {
        $query = "INSERT INTO orders 
                 (user_id, total, status, payment_method, address, created_at) 
                 VALUES (:user_id, :total, :status, :payment_method, :address, NOW())";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':total' => $data['total'],
            ':status' => $data['status'] ?? 'pending',
            ':payment_method' => $data['payment_method'] ?? null,
            ':address' => $data['address'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Menambahkan item ke order
     * @param int $orderId ID order
     * @param int $menuId ID menu
     * @param int $quantity Jumlah
     * @param float $price Harga per item
     * @return bool Berhasil/tidak
     */
    public function addItem(int $orderId, int $menuId, int $quantity, float $price): bool
    {
        $query = "INSERT INTO order_items 
                 (order_id, menu_id, quantity, price) 
                 VALUES (:order_id, :menu_id, :quantity, :price)";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':order_id' => $orderId,
            ':menu_id' => $menuId,
            ':quantity' => $quantity,
            ':price' => $price
        ]);
    }

    /**
     * Mengambil data order berdasarkan ID
     * @param int $orderId
     * @return array|null Data order atau null jika tidak ditemukan
     */
    public function getById(int $orderId): ?array
    {
        $query = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $orderId]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Mengambil semua item dari suatu order
     * @param int $orderId
     * @return array Daftar item order
     */
    public function getItems(int $orderId): array
    {
        $query = "SELECT oi.*, m.name, m.image 
                 FROM order_items oi
                 JOIN menus m ON oi.menu_id = m.id
                 WHERE oi.order_id = :order_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':order_id' => $orderId]);

        return $stmt->fetchAll();
    }

    /**
     * Mengupdate status order
     * @param int $orderId
     * @param string $status [pending, processing, completed, cancelled]
     * @return bool Berhasil/tidak
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $query = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':id' => $orderId,
            ':status' => $status
        ]);
    }

    /**
     * Mengambil semua order oleh user tertentu
     * @param int $userId
     * @return array Daftar order
     */
    
    public function getByUser(int $userId): array
    {
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll();
    }
}