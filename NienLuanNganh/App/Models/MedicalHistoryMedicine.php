<?php

namespace App\Models;

use PDO;
use Exception;

class MedicalHistoryMedicine
{
    private PDO $db;

    public int $id;
    public int $medical_history_id;
    public int $medicine_id;
    public int $quantity;
    public string $dosage;
    public string $notes;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Gán dữ liệu từ mảng đầu vào vào thuộc tính của đối tượng
     */
    public function fill(array $data): static
    {
        if (isset($data[0]) && is_array($data[0])) {
            $data = $data[0];
        }

        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->medicine_id = isset($data['medicine_id']) ? (int)$data['medicine_id'] : 0;
        $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
        $this->dosage = $data['dosage'] ?? '';
        $this->notes = $data['notes'] ?? '';

        return $this;
    }


    /**
     * Lưu mới chi tiết đơn thuốc vào DB
     */
    public function save(): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO medical_history_medicines 
            (medical_history_id, medicine_id, quantity, dosage, notes) 
            VALUES (:medical_history_id, :medicine_id, :quantity, :dosage, :notes)"
        );

        return $stmt->execute([
            'medical_history_id' => $this->medical_history_id,
            'medicine_id' => $this->medicine_id,
            'quantity' => $this->quantity,
            'dosage' => $this->dosage,
            'notes' => $this->notes,
        ]);
    }

    /**
     * Cập nhật chi tiết đơn thuốc theo id
     */
    public function update(): bool
    {
        if (!$this->id) {
            throw new Exception("ID is required for update");
        }

        $stmt = $this->db->prepare(
            "UPDATE medical_history_medicines SET 
                medical_history_id = :medical_history_id,
                medicine_id = :medicine_id,
                quantity = :quantity,
                dosage = :dosage,
                notes = :notes
            WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $this->id,
            'medical_history_id' => $this->medical_history_id,
            'medicine_id' => $this->medicine_id,
            'quantity' => $this->quantity,
            'dosage' => $this->dosage,
            'notes' => $this->notes,
        ]);
    }

    /**
     * Xóa chi tiết đơn thuốc theo id
     */
    public function delete(): bool
    {
        if (!$this->id) {
            throw new Exception("ID is required for delete");
        }

        $stmt = $this->db->prepare("DELETE FROM medical_history_medicines WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

    /**
     * Tìm chi tiết đơn thuốc theo id
     */
    public function find(int $id): ?static
    {
        $stmt = $this->db->prepare("SELECT * FROM medical_history_medicines WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->fill($row);
            return $this;
        }
        return null;
    }

    /**
     * Lấy danh sách các chi tiết thuốc trong một lịch sử bệnh án
     */
    public function getByMedicalHistoryId(int $medical_history_id): array
    {
        $stmt = $this->db->prepare("SELECT mhm.*, m.name as medicine_name 
                                    FROM medical_history_medicines mhm
                                    JOIN medicines m ON mhm.medicine_id = m.id
                                    WHERE mhm.medical_history_id = :medical_history_id");
        $stmt->execute(['medical_history_id' => $medical_history_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByMedicalHistoryAll(): array
    {
        $stmt = $this->db->prepare("
        SELECT mmm.*, m.name AS medicine_name
        FROM medical_history_medicines mmm
        JOIN medicines m ON mmm.medicine_id = m.id
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}