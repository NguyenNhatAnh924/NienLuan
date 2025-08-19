<?php

namespace App\Models;

use PDO;
use Exception;

class MedicalHistory
{
    private PDO $db;

    public int $id;
    public int $appointment_id;
    public int $patient_id;
    public int $guest_id;
    public int $doctor_id;
    public string $diagnosis;
    public ?string $treatment_plan;  // có thể null
    public ?string $notes;           // có thể null
    public string $patient_type;
    public string $created_at;
    public string $updated_at;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Gán dữ liệu từ mảng vào thuộc tính của đối tượng
     */
    public function fill(array $data): static
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->appointment_id = isset($data['appointment_id']) ? (int)$data['appointment_id'] : 0;
        $this->patient_id = isset($data['patient_id']) ? (int)$data['patient_id'] : 0;
        $this->guest_id = isset($data['guest_id']) ? (int)$data['guest_id'] : null;
        $this->patient_type = $data['patient_type'] ?? 'user'; // ✅ thêm dòng này, mặc định là 'user'
        $this->doctor_id = isset($data['doctor_id']) ? (int)$data['doctor_id'] : 0;
        $this->diagnosis = $data['diagnosis'] ?? '';
        $this->treatment_plan = $data['treatment_plan'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->created_at = $data['created_at'] ?? '';
        $this->updated_at = $data['updated_at'] ?? '';

        return $this;
    }


    /**
     * Thêm mới bản ghi
     */
    public function save(): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO medical_history 
            (appointment_id, patient_id, doctor_id, diagnosis, treatment_plan, notes, created_at, updated_at)
         VALUES 
            (:appointment_id, :patient_id, :doctor_id, :diagnosis, :treatment_plan, :notes, NOW(), NOW())"
        );

        $result = $stmt->execute([
            'appointment_id' => $this->appointment_id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'diagnosis' => $this->diagnosis,
            'treatment_plan' => $this->treatment_plan,
            'notes' => $this->notes,
        ]);

        // ✅ Lưu lại ID sau khi insert
        if ($result) {
            $this->id = $this->db->lastInsertId();
        }

        return $result;
    }

    public function saveForGuest(): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO medical_history 
        (appointment_id, guest_id, patient_type, doctor_id, diagnosis, treatment_plan, notes, created_at, updated_at)
        VALUES 
        (:appointment_id, :guest_id, 'guest', :doctor_id, :diagnosis, :treatment_plan, :notes, NOW(), NOW())"
        );

        $result = $stmt->execute([
            'appointment_id'   => $this->appointment_id,
            'guest_id'         => $this->guest_id,
            'doctor_id'        => $this->doctor_id,
            'diagnosis'        => $this->diagnosis,
            'treatment_plan'   => $this->treatment_plan,
            'notes'            => $this->notes,
        ]);

        if ($result) {
            $this->id = $this->db->lastInsertId();
        }

        return $result;
    }



    /**
     * Cập nhật bản ghi theo id
     */
    public function update(): bool
    {
        if (!$this->id) {
            throw new Exception("ID is required for update");
        }

        $stmt = $this->db->prepare(
            "UPDATE medical_history SET
                appointment_id = :appointment_id,
                patient_id = :patient_id,
                doctor_id = :doctor_id,
                diagnosis = :diagnosis,
                treatment_plan = :treatment_plan,
                notes = :notes,
                updated_at = NOW()
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'diagnosis' => $this->diagnosis,
            'treatment_plan' => $this->treatment_plan,
            'notes' => $this->notes,
        ]);
    }

    /**
     * Xóa bản ghi theo id
     */
    public function delete(): bool
    {
        if (!$this->id) {
            throw new Exception("ID is required for delete");
        }

        $stmt = $this->db->prepare("DELETE FROM medical_history WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

    /**
     * Tìm bản ghi theo id
     */
    public function find(int $id): ?static
    {
        $stmt = $this->db->prepare("SELECT * FROM medical_history WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->fill($row);
            return $this;
        }
        return null;
    }

    /**
     * Lấy bản ghi theo appointment_id (nếu cần)
     */
    public function findByAppointmentId(int $appointment_id): ?static
    {
        $stmt = $this->db->prepare("SELECT * FROM medical_history WHERE appointment_id = :appointment_id");
        $stmt->execute(['appointment_id' => $appointment_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->fill($row);
            return $this;
        }
        return null;
    }

    /**
     * Lấy tất cả bản ghi
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM medical_history ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPatientId(int $patient_id): array
    {
        $stmt = $this->db->prepare("
        SELECT 
            mh.id AS history_id,
            mh.appointment_id,
            mh.created_at,
            mh.diagnosis,
            mh.treatment_plan,
            mh.notes,
            a.appointment_date,
            a.session,
            a.symptoms,
            u.name AS doctor_name
        FROM medical_history mh
        LEFT JOIN appointments a ON mh.appointment_id = a.id
        LEFT JOIN users u ON mh.doctor_id = u.id
        WHERE mh.patient_id = :patient_id
        ORDER BY mh.created_at DESC
    ");
        $stmt->execute(['patient_id' => $patient_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}