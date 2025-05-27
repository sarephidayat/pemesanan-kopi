<?php
class Menu
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM menus");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO menus (name, price, description) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['price'], $data['description']]);
        return $this->db->lastInsertId();
    }

    // Method lainnya: update, delete, search, dll
}