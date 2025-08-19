<?php

namespace App\Models;

use PDO;
use Exception;

class Service
{
    private PDO $db;

    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public bool $is_active;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Gán dữ liệu từ mảng đầu vào vào thuộc tính đối tượng
     */
    public function fill(array $data) : Service
    {
        $this->name = $data['service_name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->price = isset($data['service_price']) ? (float)$data['service_price'] : 0.0;
        $this->is_active = isset($data['is_active']) ? (bool)$data['is_active'] : true;
        return $this;
    }

    /**
     * Lưu dịch vụ mới vào cơ sở dữ liệu
     */
    public function save(): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO services (name, description, price, is_active) 
             VALUES (:name, :description, :price, :is_active)'
        );

        return $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active
        ]);
    }

    /**
     * Tìm dịch vụ theo ID
     */
    public function find(int $id): ?Service
    {
        $stmt = $this->db->prepare('SELECT * FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id = (int)$row['id'];
            $this->fill($row);
            return $this;
        }
        return null;
    }

    /**
     * Tìm dịch vụ theo tên (tương đối, không phân biệt hoa thường)
     */
    public function findByName(string $name): array
    {
        $stmt = $this->db->prepare('SELECT * FROM services WHERE name LIKE :name ORDER BY name');
        $stmt->execute(['name' => "%{$name}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật thông tin dịch vụ trong cơ sở dữ liệu
     */
    public function update(): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE services SET name = :name, description = :description, price = :price, is_active = :is_active WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active
        ]);
    }

    /**
     * Xóa dịch vụ khỏi cơ sở dữ liệu
     */
    public function delete(): bool
    {
        $stmt = $this->db->prepare('DELETE FROM services WHERE id = :id');
        return $stmt->execute(['id' => $this->id]);
    }

    /**
     * Lấy danh sách tất cả dịch vụ đang hoạt động (is_active = true)
     */
    public function allActive(): array
    {
        $stmt = $this->db->query('SELECT * FROM services WHERE is_active = 1 ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách tất cả dịch vụ (bao gồm cả không hoạt động)
     */
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM services ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}