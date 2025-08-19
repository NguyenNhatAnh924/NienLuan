<?php

namespace App\Models;

use PDO;
use Exception;

class WorkSession
{
    private PDO $db;

    public int $id;
    public string $name;
    public string $start_time;
    public string $end_time;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Gán dữ liệu từ mảng vào đối tượng
     */
    public function fill(array $data): static
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->name = (string)($data['name'] ?? '');
        $this->start_time = $data['start_time'] ?? '00:00:00';
        $this->end_time = $data['end_time'] ?? '00:00:00';
        return $this;
    }

    /**
     * Gán dữ liệu từ 1 dòng truy vấn CSDL
     */
    public function fillFromDbRow(array $row): void
    {
        $this->id = (int)($row['id'] ?? 0);
        $this->name = (string)($row['name'] ?? '');
        $this->start_time = $row['start_time'] ?? '00:00:00';
        $this->end_time = $row['end_time'] ?? '00:00:00';
    }

    /**
     * Tạo mới buổi làm việc
     */
    public function create(): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO work_sessions (name, start_time, end_time)
            VALUES (:name, :start_time, :end_time)
        ");

        $result = $stmt->execute([
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time
        ]);

        if ($result) {
            $this->id = (int)$this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Cập nhật buổi làm việc
     */
    public function update(): bool
    {
        if (!$this->id) throw new Exception("ID is required for update");

        $stmt = $this->db->prepare("
            UPDATE work_sessions
            SET name = :name,
                start_time = :start_time,
                end_time = :end_time
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time
        ]);
    }

    /**
     * Xóa buổi làm việc
     */
    public function delete(): bool
    {
        if (!$this->id) throw new Exception("ID is required for delete");

        $stmt = $this->db->prepare("DELETE FROM work_sessions WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

    /**
     * Lấy thông tin buổi làm việc theo ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM work_sessions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Lấy toàn bộ buổi làm việc
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM work_sessions ORDER BY FIELD(name, 'Sáng','Chiều','Tối')");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra tên buổi làm việc đã tồn tại chưa
     */
    public function existsByName(string $name): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM work_sessions WHERE name = :name");
        $stmt->execute(['name' => $name]);
        return $stmt->fetchColumn() > 0;
    }
}