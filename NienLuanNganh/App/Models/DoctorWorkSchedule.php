<?php

namespace App\Models;

use PDO;
use Exception;

/**
 * Class DoctorWorkSchedule
 * Quản lý lịch làm việc của bác sĩ
 */
class DoctorWorkSchedule
{
    private PDO $db;

    public int $id;
    public int $doctor_id;
    public string $doctor_name;
    public ?string $specific_date;
    public int $session_id;
    public string $status; // pending, approved, cancelled
    public ?string $notes;
    public string $created_at;
    public string $updated_at;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function find(int $id): ?DoctorWorkSchedule
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM doctor_work_schedules dws
            WHERE dws.id = :id
        ");
        $stmt->execute(['id' => $id]);

        if ($row = $stmt->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }

    /**
     * Gán dữ liệu từ mảng vào thuộc tính đối tượng
     */
    public function fill(array $data): static
    {
        // $this->id = (int)($data['id'] ?? 0);
        $this->doctor_id = (int)($data['doctor_id'] ?? 0);
        $this->doctor_name = (string)($data['doctor_name'] ?? '');
        $this->specific_date = $data['specific_date'] ?? null;
        $this->session_id = (int)($data['session_id'] ?? 0);
        $this->status = $data['status'] ?? 'pending';
        $this->notes = $data['notes'] ?? null;
        $this->created_at = $data['created_at'] ?? '';
        $this->updated_at = $data['updated_at'] ?? '';
        return $this;
    }

    /**
     * Gán dữ liệu từ hàng dữ liệu trong cơ sở dữ liệu
     */
    public function fillFromDbRow(array $row): void
    {
        $this->id = (int)($row['id'] ?? 0);
        $this->doctor_id = (int)($row['doctor_id'] ?? 0);
        $this->doctor_name = (string)($row['doctor_name'] ?? '');
        $this->specific_date = $row['specific_date'] ?? null;
        $this->session_id = (int)($row['session_id'] ?? 0);
        $this->status = $row['status'] ?? 'pending';
        $this->notes = $row['notes'] ?? null;
        $this->created_at = $row['created_at'] ?? '';
        $this->updated_at = $row['updated_at'] ?? '';
    }

    /**
     * Kiểm tra ngày có nằm trong quá khứ không
     */


    public function validate(array $data): array
    {
        $errors = [];

        // Gán dữ liệu vào model trước để dùng cho exists()
        $this->fill($data);

        // Lấy ngày hôm nay
        $today = new \DateTimeImmutable('today');

        // Kiểm tra ngày làm việc
        if (empty($this->specific_date)) {
            $errors['specific_date'] = 'Vui lòng chọn ngày làm việc.';
        } else {
            $date = \DateTimeImmutable::createFromFormat('Y-m-d', $this->specific_date);

            if (!$date || $date->format('Y-m-d') !== $this->specific_date) {
                $errors['specific_date'] = 'Ngày không hợp lệ.';
            } elseif ($date < $today) {
                $errors['specific_date'] = 'Không thể đăng ký lịch cho ngày trong quá khứ.';
            }
        }

        // Kiểm tra buổi làm việc
        if (empty($this->session_id)) {
            $errors['session_id'] = 'Vui lòng chọn buổi làm việc.';
        }

        // Kiểm tra trạng thái
        if (empty($this->status)) {
            $errors['status'] = 'Vui lòng chọn trạng thái lịch.';
        } elseif (!in_array($this->status, ['pending', 'approved', 'cancelled'])) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        // Kiểm tra trùng lịch
        if (empty($errors) && $this->exists()) {
            $errors['exists'] = 'Lịch làm việc này đã tồn tại.';
        }

        return $errors;
    }

    public function validateUpdate(array $data): array
    {
        $errors = [];

        // Gán dữ liệu vào model trước để dùng cho exists()
        $this->fill($data);

        // Lấy ngày hôm nay
        $today = new \DateTimeImmutable('today');

        // Kiểm tra ngày làm việc
        if (empty($this->specific_date)) {
            $errors['specific_date'] = 'Vui lòng chọn ngày làm việc.';
        } else {
            $date = \DateTimeImmutable::createFromFormat('Y-m-d', $this->specific_date);

            if (!$date || $date->format('Y-m-d') !== $this->specific_date) {
                $errors['specific_date'] = 'Ngày không hợp lệ.';
            } elseif ($date < $today) {
                $errors['specific_date'] = 'Không thể đăng ký lịch cho ngày trong quá khứ.';
            }
        }

        // Kiểm tra buổi làm việc
        if (empty($this->session_id)) {
            $errors['session_id'] = 'Vui lòng chọn buổi làm việc.';
        }

        // Kiểm tra trạng thái
        if (empty($this->status)) {
            $errors['status'] = 'Vui lòng chọn trạng thái lịch.';
        } elseif (!in_array($this->status, ['pending', 'approved', 'cancelled'])) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        return $errors;
    }

    /**
     * Lưu lịch làm việc mới
     */
    public function save(): bool
    {

        $stmt = $this->db->prepare("
            INSERT INTO doctor_work_schedules 
            (doctor_id, specific_date, session_id, status, notes, created_at, updated_at)
            VALUES 
            (:doctor_id, :specific_date, :session_id, :status, :notes, NOW(), NOW())
        ");

        $result = $stmt->execute([
            'doctor_id' => $this->doctor_id,
            'specific_date' => $this->specific_date,
            'session_id' => $this->session_id,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        if ($result) {
            $this->id = (int)$this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Cập nhật lịch làm việc
     */
    public function update(): bool
    {
        if (!$this->id) throw new Exception("ID is required for update");

        $stmt = $this->db->prepare("
            UPDATE doctor_work_schedules SET
                specific_date = :specific_date,
                session_id = :session_id,
                status = :status,
                notes = :notes,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $this->id,
            'specific_date' => $this->specific_date,
            'session_id' => $this->session_id,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);
    }

    /**
     * Xoá lịch làm việc
     */
    public function delete(): bool
    {
        $stmt = $this->db->prepare("DELETE FROM doctor_work_schedules WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

    /**
     * Lấy toàn bộ lịch đã được duyệt của bác sĩ
     */
    public function getAllSchedulesByDoctor(int $doctorId): array
    {
        $stmt = $this->db->prepare("
            SELECT dws.*, u.name AS doctor_name, ws.name AS session_name, ws.start_time, ws.end_time
            FROM doctor_work_schedules dws
            JOIN doctors d ON dws.doctor_id = d.doctor_id
            JOIN users u ON d.doctor_id = u.id
            JOIN work_sessions ws ON dws.session_id = ws.id
            WHERE dws.doctor_id = :doctor_id
            ORDER BY dws.specific_date ASC
        ");

        $stmt->execute(['doctor_id' => $doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả lịch (không lọc trạng thái) của một bác sĩ
     */
    public function allByDoctor(int $doctor_id): array
    {
        $stmt = $this->db->prepare("
            SELECT dws.*, u.name AS doctor_name, ws.name AS session_name, ws.start_time, ws.end_time
            FROM doctor_work_schedules dws
            JOIN users u ON dws.doctor_id = u.id
            JOIN work_sessions ws ON dws.session_id = ws.id
            WHERE dws.doctor_id = :doctor_id
            ORDER BY dws.specific_date ASC
        ");

        $stmt->execute(['doctor_id' => $doctor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = $this->db->prepare("
            SELECT dws.*, u.name AS doctor_name, ws.name AS session_name, ws.start_time, ws.end_time
            FROM doctor_work_schedules dws
            JOIN users u ON dws.doctor_id = u.id
            JOIN work_sessions ws ON dws.session_id = ws.id
            ORDER BY dws.specific_date DESC
        ");

        $stmt->execute([]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Kiểm tra xem lịch làm việc đã tồn tại chưa
     */
    public function exists(): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM doctor_work_schedules
            WHERE doctor_id = :doctor_id
              AND session_id = :session_id
              AND specific_date = :specific_date
        ");

        $stmt->execute([
            'doctor_id' => $this->doctor_id,
            'session_id' => $this->session_id,
            'specific_date' => $this->specific_date
        ]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Lấy toàn bộ lịch làm việc của tất cả bác sĩ
     */
    public function allSchedules(): array
    {
        $stmt = $this->db->query("
            SELECT 
                dws.*, 
                u.name AS doctor_name,
                ws.name AS session_name,
                ws.start_time,
                ws.end_time
            FROM doctor_work_schedules dws
            JOIN users u ON dws.doctor_id = u.id
            JOIN work_sessions ws ON dws.session_id = ws.id
            ORDER BY 
                u.name ASC,
                dws.specific_date ASC,
                ws.start_time
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorWorkDaysThisMonth(): array
    {
        $sql = "
        -- Đếm số buổi làm của bác sĩ từ doctor_work_schedules
        SELECT 
            u.id AS user_id,
            u.name AS user_name,
            u.role,
            COUNT(DISTINCT CONCAT(dws.specific_date, '-', dws.session_id)) AS work_sessions
        FROM 
            users u
        LEFT JOIN doctors d ON u.id = d.doctor_id
        LEFT JOIN doctor_work_schedules dws ON dws.doctor_id = d.doctor_id
            AND MONTH(dws.specific_date) = MONTH(CURDATE())
            AND YEAR(dws.specific_date) = YEAR(CURDATE())
        WHERE 
            u.role = 'doctor'
        GROUP BY 
            u.id, u.name, u.role

        UNION ALL

        -- Giả định admin và lễ tân làm việc mỗi ngày có lịch hẹn phát sinh
        SELECT 
            u.id AS user_id,
            u.name AS user_name,
            u.role,
            COUNT(DISTINCT a.appointment_date) AS work_sessions
        FROM 
            users u
        LEFT JOIN appointments a ON a.appointment_date IS NOT NULL
            AND MONTH(a.appointment_date) = MONTH(CURDATE())
            AND YEAR(a.appointment_date) = YEAR(CURDATE())
        WHERE 
            u.role IN ('admin', 'reception')
        GROUP BY 
            u.id, u.name, u.role
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}