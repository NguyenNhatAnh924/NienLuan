<?php

namespace App\Models;

use PDO;
use Exception;

class Doctor extends User
{
    public string $specialization = '';
    public string $clinic_room = '';
    public string $working_time = '';
    public int $experience_years = 0;
    public string $introduction = '';
    public string $work_status;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    // Lấy thông tin bác sĩ (từ bảng users + doctors)
    public function find(int $id): ?Doctor
    {
        $stmt = $this->db->prepare("
            SELECT u.*, d.specialization, d.clinic_room, d.working_time, d.experience_years, d.introduction
            FROM users u
            JOIN doctors d ON u.id = d.doctor_id
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $id]);

        if ($row = $stmt->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }

    // Lưu thông tin user và doctor
    public function save(): bool
    {
        $isNew = ($this->user_id <= 0);
        $result = parent::save();

        if (!$result) return false;
        if ($isNew) {
            $stmt = $this->db->prepare("
                INSERT INTO doctors (doctor_id, specialization, clinic_room, working_time, experience_years, introduction, work_status)
                VALUES (:doctor_id, :specialization, :clinic_room, :working_time, :experience_years, :introduction, :work_status)
            ");
        } else {
            $stmt = $this->db->prepare("
                UPDATE doctors 
                SET specialization = :specialization,
                    clinic_room = :clinic_room,
                    working_time = :working_time,
                    experience_years = :experience_years,
                    introduction = :introduction,
                    work_status = :work_status
                WHERE doctor_id = :doctor_id
            ");
        }

        return $stmt->execute([
            'doctor_id' => $this->user_id,
            'specialization' => $this->specialization,
            'clinic_room' => $this->clinic_room,
            'working_time' => $this->working_time,
            'experience_years' => $this->experience_years,
            'introduction' => $this->introduction,
            'work_status' => $this->work_status
        ]);
    }

    // Điền dữ liệu bác sĩ từ DB
    protected function fillFromDbRow(array $row): void
    {
        parent::fillFromDbRow($row);
        $this->specialization = $row['specialization'] ?? '';
        $this->clinic_room = $row['clinic_room'] ?? '';
        $this->working_time = $row['working_time'] ?? '';
        $this->experience_years = (int)($row['experience_years'] ?? 0);
        $this->introduction = $row['introduction'] ?? '';
        $this->work_status  = $row['work_status'] ?? '';
    }

    // Nạp dữ liệu từ form hoặc controller
    public function fill(array $data): Doctor
    {
        parent::fill($data);

        $this->specialization = $data['specialization'] ?? '';
        $this->clinic_room = $data['clinic_room'] ?? '';
        $this->working_time = $data['working_time'] ?? '';
        $this->experience_years = isset($data['experience_years']) ? (int)$data['experience_years'] : 0;
        $this->introduction = $data['introduction'] ?? '';
        $this->work_status = $data['work_status'] ?? '';
        return $this;
    }

    // Lấy danh sách tất cả bác sĩ
    public function all(): array
    {
        $doctors = [];

        $stmt = $this->db->query("
            SELECT u.*,u.name, d.specialization, d.clinic_room, d.working_time, d.experience_years, d.introduction, d.work_status 
            FROM users u
            JOIN doctors d ON u.id = d.doctor_id
            WHERE u.role = 'doctor'
            ORDER BY u.name ASC
        ");

        while ($row = $stmt->fetch()) {
            $doctor = new Doctor($this->db);
            $doctor->fillFromDbRow($row);
            $doctors[] = $doctor;
        }

        return $doctors;
    }

    // Lấy danh sách tất cả bác sĩ
    public function allDoctor(): array
    {
        $doctors = [];

        $stmt = $this->db->query("
            SELECT u.*, d.specialization, d.clinic_room, d.working_time, d.experience_years, d.introduction, d.work_status 
            FROM users u
            JOIN doctors d ON u.id = d.doctor_id
            WHERE u.role = 'doctor' AND d.work_status = 'Đang làm việc'
            ORDER BY CAST(SUBSTRING(u.name, 6) AS UNSIGNED) ASC
        ");

        while ($row = $stmt->fetch()) {
            $doctor = new Doctor($this->db);
            $doctor->fillFromDbRow($row);
            $doctors[] = $doctor;
        }

        return $doctors;
    }
}