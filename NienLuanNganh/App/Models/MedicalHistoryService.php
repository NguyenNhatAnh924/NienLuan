<?php

namespace App\Models;

use PDO;
use Exception;

class MedicalHistoryService
{
    private PDO $db;

    public int $id;
    public int $medical_history_id;
    public int $service_id;
    public int $quantity = 1;
    public string $notes = '';

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    // Gán dữ liệu từ mảng đầu vào cho thuộc tính của đối tượng

    public function fill(array $data): static
    {
        
        if (isset($data[0]) && is_array($data[0])) {
            $data = $data[0];
        }

        $this->service_id = $data['service_id'] ?? 0;
        $this->quantity = $data['quantity'] ?? 1;
        $this->notes = $data['notes'] ?? '';
        return $this;
    }

    // Thêm bản ghi chi tiết dịch vụ mới vào cơ sở dữ liệu
    public function save(): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO medical_history_services (medical_history_id, service_id, quantity, notes)
             VALUES (:medical_history_id, :service_id, :quantity, :notes)'
        );

        return $stmt->execute([
            'medical_history_id' => $this->medical_history_id,
            'service_id' => $this->service_id,
            'quantity' => $this->quantity,
            'notes' => $this->notes
        ]);
    }

    // Cập nhật chi tiết dịch vụ
    public function update(): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE medical_history_services SET medical_history_id = :medical_history_id, service_id = :service_id, quantity = :quantity, notes = :notes WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $this->id,
            'medical_history_id' => $this->medical_history_id,
            'service_id' => $this->service_id,
            'quantity' => $this->quantity,
            'notes' => $this->notes
        ]);
    }

    // Xóa chi tiết dịch vụ
    public function delete(): bool
    {
        $stmt = $this->db->prepare('DELETE FROM medical_history_services WHERE id = :id');
        return $stmt->execute(['id' => $this->id]);
    }

    // Tìm chi tiết dịch vụ theo ID
    public function find(int $id): ?static
    {
        $stmt = $this->db->prepare('SELECT * FROM medical_history_services WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id = (int)$row['id'];
            $this->medical_history_id = (int)$row['medical_history_id'];
            $this->service_id = (int)$row['service_id'];
            $this->quantity = (int)$row['quantity'];
            $this->notes = $row['notes'] ?? '';
            return $this;
        }
        return null;
    }

    // Lấy danh sách chi tiết dịch vụ theo medical_history_id
    public function getByMedicalHistoryId(int $medical_history_id): array
    {
        $stmt = $this->db->prepare(
            'SELECT mhs.*, s.name AS service_name 
             FROM medical_history_services mhs
             JOIN services s ON mhs.service_id = s.id
             WHERE mhs.medical_history_id = :medical_history_id'
        );

        $stmt->execute(['medical_history_id' => $medical_history_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}