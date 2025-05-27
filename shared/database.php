<?php
// shared/database.php

class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'db_pemesanan_kopinuri';
    protected $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // Method tambahan untuk memudahkan query
    public function query($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}